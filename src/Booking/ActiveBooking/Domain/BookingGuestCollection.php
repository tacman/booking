<?php

namespace Booking\Booking\ActiveBooking\Domain;

final class BookingGuestCollection
{
    private array $bookingGuest;

    public function __construct(BookingGuest ...$bookingGuest)
    {
        $this->bookingGuest = $bookingGuest;
    }

    public function serialize(): array
    {
        return array_map(static fn (BookingGuest $bookingGuest) => $bookingGuest->serialize(), $this->bookingGuest);
    }
}
