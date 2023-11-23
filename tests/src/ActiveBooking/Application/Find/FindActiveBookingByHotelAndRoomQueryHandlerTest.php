<?php

namespace Booking\Tests\ActiveBooking\Application\Find;

use Booking\Booking\ActiveBooking\Application\Find\ActiveBookingResponse;
use Booking\Booking\ActiveBooking\Application\Find\FindActiveBookingByHotelAndRoomQuery;
use Booking\Booking\ActiveBooking\Application\Find\FindActiveBookingByHotelAndRoomQueryHandler;
use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingNotFound;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Tests\ActiveBooking\Domain\ActiveBookingMother;
use Booking\Tests\ActiveBooking\Domain\ActiveBookingRepositoryTrait;
use Booking\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;

class FindActiveBookingByHotelAndRoomQueryHandlerTest extends UnitTestCase
{
    use ActiveBookingRepositoryTrait;

    private FindActiveBookingByHotelAndRoomQueryHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->handler = new FindActiveBookingByHotelAndRoomQueryHandler(
            $this->repository()
        );
    }

    /**
     * @throws InvalidValueException
     * @throws ActiveBookingNotFound
     */
    public function testShouldReturnActiveBookingResponse(): void
    {
        $activeBooking = ActiveBookingMother::create()->build();

        $this->shouldCallFindOneBy(
            [
                'hotel' => $activeBooking->hotel->value(),
                'room' => $activeBooking->room->value,
            ],
            [],
            $activeBooking
        );

        $response = $this->handler->__invoke(
            new FindActiveBookingByHotelAndRoomQuery(
                $activeBooking->hotel->value(),
                $activeBooking->room->value
            )
        );

        $this->assertEquals($this->expectedResponse($activeBooking), $response);
    }

    /**
     * @throws InvalidValueException
     * @throws ActiveBookingNotFound
     */
    public function testActiveBookingNotFound(): void
    {
        $activeBooking = ActiveBookingMother::create()->build();

        $this->shouldCallFindOneBy(
            [
                'hotel' => $activeBooking->hotel->value(),
                'room' => $activeBooking->room->value,
            ],
            [],
            null
        );

        $this->expectException(ActiveBookingNotFound::class);

        $this->handler->__invoke(
            new FindActiveBookingByHotelAndRoomQuery(
                $activeBooking->hotel->value(),
                $activeBooking->room->value
            )
        );
    }

    private function expectedResponse(ActiveBooking $activeBooking): ActiveBookingResponse
    {
        return new ActiveBookingResponse(
            $activeBooking->bookingId->value(),
            $activeBooking->hotel->value(),
            $activeBooking->locator->value,
            $activeBooking->room->value,
            $activeBooking->period->checkIn->stringDate(),
            $activeBooking->period->checkOut->stringDate(),
            $activeBooking->period->numberOfNights(),
            $activeBooking->totalPax->value,
            $activeBooking->guests->serialize()
        );
    }
}
