<?php

namespace Booking\Booking\ActiveBooking\Application\Find;

use Booking\Shared\Domain\Bus\Query\Query;

final class FindActiveBookingByHotelAndRoomQuery implements Query
{
    public function __construct(
        public readonly string $hotel,
        public readonly string $room
    ) {
    }
}
