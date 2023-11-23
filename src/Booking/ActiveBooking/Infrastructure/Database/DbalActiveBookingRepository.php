<?php

namespace Booking\Booking\ActiveBooking\Infrastructure\Database;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingGuest;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingGuestCollection;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingPeriod;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingRepository;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingTotalPax;
use Booking\Shared\Domain\Exception\AlreadyStoredException;
use Booking\Shared\Domain\Exception\InternalErrorException;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Shared\Domain\ValueObject\Birthdate;
use Booking\Shared\Domain\ValueObject\CountryCode;
use Booking\Shared\Domain\ValueObject\Date;
use Booking\Shared\Domain\ValueObject\Locator;
use Booking\Shared\Domain\ValueObject\Passport;
use Booking\Shared\Domain\ValueObject\Room;
use Booking\Shared\Domain\ValueObject\Uuid;
use Booking\Shared\Infrastructure\Doctrine\Dbal\DbalRepository;
use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\map;

final class DbalActiveBookingRepository extends DbalRepository implements ActiveBookingRepository
{
    private const TABLE = 'active_booking';

    /**
     * @throws InternalErrorException
     */
    public function findOneBy(array $fields, array $order = []): ?ActiveBooking
    {
        $result = $this->findBy($fields, false, $order);

        if ($result === false) {
            return null;
        }

        return $this->toActiveBooking($result);
    }

    /**
     * @throws InternalErrorException
     * @throws AlreadyStoredException
     * @throws \JsonException
     */
    public function save(ActiveBooking $activeBooking): void
    {
        $this->insert(
            [
                'booking_id' => $activeBooking->bookingId->value(),
                'hotel' => $activeBooking->hotel->value(),
                'locator' => $activeBooking->locator->value,
                'room' => $activeBooking->room->value,
                'check_in' => $activeBooking->period->checkIn->stringDateTime(),
                'check_out' => $activeBooking->period->checkOut->stringDateTime(),
                'total_pax' => $activeBooking->totalPax->value,
                'guests' => json_encode($activeBooking->guests->serialize(), JSON_THROW_ON_ERROR),
                'created_at' => $activeBooking->createdAt->stringDateTime(),
            ]
        );
    }

    protected function table(): string
    {
        return self::TABLE;
    }

    /**
     * @throws InvalidValueException
     * @throws \JsonException
     */
    private function toActiveBooking(array $result): ActiveBooking
    {
        return new ActiveBooking(
            new Uuid($result['booking_id']),
            new Uuid($result['hotel']),
            new Locator($result['locator']),
            new Room($result['room']),
            new ActiveBookingPeriod(
                new Date($result['check_in']),
                new Date($result['check_out'])
            ),
            new ActiveBookingTotalPax($result['total_pax']),
            new ActiveBookingGuestCollection(
                ...map(
                        static function (array $guest) {
                            return new ActiveBookingGuest(
                                $guest['name'],
                                $guest['lastname'],
                                new Birthdate(new Date($guest['birthdate'])),
                                new Passport($guest['passport']),
                                new CountryCode($guest['country'])
                            );
                        },
                        json_decode($result['guests'], true, 512, JSON_THROW_ON_ERROR)
                    ),
            ),
            new Date($result['created_at'])
        );
    }
}
