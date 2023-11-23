<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\Aggregate\AggregateRoot;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Shared\Domain\ValueObject\Date;
use Booking\Shared\Domain\ValueObject\Locator;
use Booking\Shared\Domain\ValueObject\Room;
use Booking\Shared\Domain\ValueObject\Uuid;

final class ActiveBooking extends AggregateRoot
{
    public function __construct(
        public readonly Uuid $bookingId,
        public readonly Uuid $hotel,
        public readonly Locator $locator,
        public readonly Room $room,
        public readonly ActiveBookingPeriod $period,
        public readonly ActiveBookingTotalPax $totalPax,
        public readonly ActiveBookingGuestCollection $guests,
        public readonly Date $createdAt
    ) {
    }

    /**
     * @throws InvalidValueException
     */
    public static function create(
        string $bookingId,
        string $hotel,
        string $locator,
        string $room,
        string $checkIn,
        string $checkOut,
        int $totalPax,
        array $guests
    ): self {
        $activeBooking = new self(
            new Uuid($bookingId),
            new Uuid($hotel),
            new Locator($locator),
            new Room($room),
            new ActiveBookingPeriod(
                new Date($checkIn),
                new Date($checkOut)
            ),
            new ActiveBookingTotalPax($totalPax),
            new ActiveBookingGuestCollection(
                ...array_map(
                    static fn (array $guest) => ActiveBookingGuest::fromPrimitives($guest),
                    $guests
                )
            ),
            new Date()
        );

        $activeBooking->record(new ActiveBookingWasCreated(
            $activeBooking->bookingId->value(),
            $activeBooking->hotel->value(),
            $activeBooking->locator->value,
            $activeBooking->locator->value,
            $activeBooking->period->checkIn->stringDate(),
            $activeBooking->period->checkOut->stringDate(),
            $activeBooking->totalPax->value,
            $activeBooking->guests->serialize(),
        ));

        return $activeBooking;
    }

    public function serialize(): array
    {
        return [
            'bookingId' => $this->bookingId,
            'hotel' => $this->hotel,
            'locator' => $this->locator->value,
            'room' => $this->room,
            'checkIn' => $this->period->checkIn,
            'checkOut' => $this->period->checkOut,
            'numberOfNights' => $this->period->numberOfNights(),
            'totalPax' => $this->locator->value,
            'guests' => $this->guests->serialize(),
        ];
    }
}
