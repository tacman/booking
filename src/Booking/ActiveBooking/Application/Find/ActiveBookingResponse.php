<?php

namespace Booking\Booking\ActiveBooking\Application\Find;

use Booking\Shared\Domain\Bus\Query\Response;

final class ActiveBookingResponse implements Response
{
    public function __construct(
        public readonly string $bookingId,
        public readonly string $hotel,
        public readonly string $locator,
        public readonly string $room,
        public readonly string $checkIn,
        public readonly string $checkOut,
        public readonly int $numberOfNights,
        public readonly int $totalPax,
        public readonly array $guests
    ) {
    }
}
