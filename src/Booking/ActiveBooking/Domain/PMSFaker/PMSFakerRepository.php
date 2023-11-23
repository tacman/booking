<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain\PMSFaker;

use Booking\Shared\Domain\ValueObject\Date;

interface PMSFakerRepository
{
    public function findAllSinceTimestamp(?Date $date): array;
}
