<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\Exception\InvalidValueException;

final class ActiveBookingTotalPax
{
    private const MAX_PAX = 99;

    /**
     * @throws InvalidValueException
     */
    public function __construct(
        public readonly int $value
    ) {
        if ($this->value > self::MAX_PAX) {
            throw new InvalidValueException('Number of total pax per room not valid.');
        }
    }
}
