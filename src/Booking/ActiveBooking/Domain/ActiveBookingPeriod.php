<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\ValueObject\Date;

final class ActiveBookingPeriod
{
    private int $numberOfNights;

    public function __construct(
        public readonly Date $checkIn,
        public readonly Date $checkOut
    ) {
        $this->calculateNumberOfNights(
            $checkIn,
            $checkOut
        );
    }

    public function numberOfNights(): int
    {
        return $this->numberOfNights;
    }

    private function calculateNumberOfNights(Date $checkIn, Date $checkOut): void
    {
        $this->numberOfNights = $checkOut->diffInDays($checkIn);
    }
}
