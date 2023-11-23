<?php

namespace Booking\App\Controller\ActiveBooking;

use Booking\Booking\ActiveBooking\Application\Find\ActiveBookingResponse;
use Booking\Booking\ActiveBooking\Application\Find\FindActiveBookingByHotelAndRoomQuery;
use Booking\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetActiveBookingByHotelAndRoomController
{
    public function __construct(
        private readonly QueryBus $bus
    ) {
    }

    public function __invoke(string $hotel, string $room): JsonResponse
    {
        /** @var ActiveBookingResponse $response */
        $response = $this->bus->get(
            new FindActiveBookingByHotelAndRoomQuery($hotel, $room)
        );

        return new JsonResponse(
            [
                'bookingId' => $response->bookingId,
                'hotel' => $response->hotel,
                'locator' => $response->locator,
                'room' => $response->room,
                'checkIn' => $response->checkIn,
                'checkOut' => $response->checkOut,
                'numberOfNights' => $response->numberOfNights,
                'totalPax' => $response->totalPax,
                'guests' => $response->guests,
            ]
        );
    }
}
