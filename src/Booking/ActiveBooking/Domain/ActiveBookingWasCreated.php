<?php

namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\Bus\Event\DomainEvent;

class ActiveBookingWasCreated extends DomainEvent
{
    private const ROUTING_KEY = 'booking.active_booking.created';

    public function __construct(
        public readonly string $bookingId,
        public readonly string $hotel,
        public readonly string $locator,
        public readonly string $room,
        public readonly string $checkIn,
        public readonly string $checkOut,
        public readonly int $totalPax,
        public readonly array $guests
    ) {
        parent::__construct();
    }

    public static function eventName(): string
    {
        return self::ROUTING_KEY;
    }
}
