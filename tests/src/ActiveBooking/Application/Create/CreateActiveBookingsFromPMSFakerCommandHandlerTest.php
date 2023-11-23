<?php

namespace Booking\Tests\ActiveBooking\Application\Create;

use Booking\Booking\ActiveBooking\Application\Create\CreateActiveBookingsFromPMSFakerCommand;
use Booking\Booking\ActiveBooking\Application\Create\CreateActiveBookingsFromPMSFakerCommandHandler;
use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingGuest;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingGuestCollection;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingPeriod;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingTotalPax;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Shared\Domain\ValueObject\Birthdate;
use Booking\Shared\Domain\ValueObject\CountryCode;
use Booking\Shared\Domain\ValueObject\Date;
use Booking\Shared\Domain\ValueObject\Locator;
use Booking\Shared\Domain\ValueObject\Passport;
use Booking\Shared\Domain\ValueObject\Room;
use Booking\Shared\Domain\ValueObject\Uuid;
use Booking\Tests\ActiveBooking\Domain\ActiveBookingMother;
use Booking\Tests\ActiveBooking\Domain\ActiveBookingRepositoryTrait;
use Booking\Tests\ActiveBooking\Domain\PMSRepositoryTrait;
use Booking\Tests\Shared\Infrastructure\PhpUnit\Trait\EventBusTrait;
use Booking\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;

class CreateActiveBookingsFromPMSFakerCommandHandlerTest extends UnitTestCase
{
    use EventBusTrait;

    use ActiveBookingRepositoryTrait;

    use PMSRepositoryTrait;

    public const UUID = '00000000-0000-0000-0000-000000000000';

    private CreateActiveBookingsFromPMSFakerCommandHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateActiveBookingsFromPMSFakerCommandHandler(
            $this->pmsRepository(),
            $this->repository(),
            $this->eventBus()
        );
    }

    /**
     * @throws InvalidValueException
     * @throws \JsonException
     */
    public function testShouldCreateActiveBookingsFromPMSFaker(): void
    {
        $lastActiveBooking = ActiveBookingMother::create()->build();
        $expectedActiveBooking = $this->validActiveBookingFromPmsBooking(
            $this->pmsBookings()[0]
        );

        $this->shouldCallFindOneBy(
            [],
            [
                'created_at' => 'DESC',
            ],
            $lastActiveBooking
        );

        $this->shouldCallFindAllSinceTimeStamp(
            $lastActiveBooking->createdAt,
            $this->pmsBookings()
        );

        $this->shouldCallSave($expectedActiveBooking);

        $this->shouldCallDispatch();

        $this->handler->__invoke(new CreateActiveBookingsFromPMSFakerCommand());
    }

    /**
     * @throws InvalidValueException
     * @throws \JsonException
     */
    public function testShouldCreateActiveBookingsFromPMSFakerWithTs0(): void
    {
        $this->shouldCallFindOneBy([], [
            'created_at' => 'DESC',
        ], null);
        $expectedActiveBooking = $this->validActiveBookingFromPmsBooking(
            $this->pmsBookings()[0]
        );

        $this->shouldCallFindAllSinceTimeStamp(
            null,
            $this->pmsBookings()
        );

        $this->shouldCallSave($expectedActiveBooking);

        $this->shouldCallDispatch();

        $this->handler->__invoke(new CreateActiveBookingsFromPMSFakerCommand());
    }

    /**
     * @throws \JsonException
     */
    private function pmsBookings(): array
    {
        $json = '
        [{
            "hotel_id": "49001",
            "hotel_name": "Hotel con ID Externo 49001",
            "guest": {
                "name": "Juan",
                "lastname": "Madrigal",
                "birthdate": "1999-12-06",
                "passport": "WF-1495889-GR",
                "country": "ES"
            },
            "booking": {
                "locator": "61F80321790C5",
                "room": "291",
                "check_in": "2022-01-31",
                "check_out": "2022-02-08",
                "pax": {
                    "adults": 1,
                    "kids": 0,
                    "babies": 0
                }
            },
            "created": "2022-01-31 17:39:38",
            "signature": "e8b558125c709621bd5a80ca25f772cc7a3a4b8b0b86478f355740af5d7558a8"
          }]
        ';

        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws InvalidValueException
     */
    private function validActiveBookingFromPmsBooking(array $pmsBooking): ActiveBooking
    {
        return new ActiveBooking(
            new Uuid(self::UUID),
            new Uuid('5ab1d247-19ea-4850-9242-2d3ffbbdb58d'),
            new Locator($pmsBooking['booking']['locator']),
            new Room($pmsBooking['booking']['room']),
            new ActiveBookingPeriod(
                new Date($pmsBooking['booking']['check_in']),
                new Date($pmsBooking['booking']['check_out'])
            ),
            new ActiveBookingTotalPax(1),
            new ActiveBookingGuestCollection(
                new ActiveBookingGuest(
                    $pmsBooking['guest']['name'],
                    $pmsBooking['guest']['lastname'],
                    new Birthdate(new Date($pmsBooking['guest']['birthdate'])),
                    new Passport($pmsBooking['guest']['passport']),
                    new CountryCode($pmsBooking['guest']['country'])
                )
            ),
            new Date()
        );
    }
}
