<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain;

final class ActiveBookingGuestCollection
{
    private array $bookingGuest;

    public function __construct(ActiveBookingGuest ...$bookingGuest)
    {
        $this->bookingGuest = $bookingGuest;
    }

    public function serialize(): array
    {
        return array_map(static fn (ActiveBookingGuest $bookingGuest) => $bookingGuest->serialize(), $this->bookingGuest);
    }
}
