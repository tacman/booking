<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain\PMSFaker;

interface PMSFakerRepository
{
    public function findAllFromTimeStamp(int $timestamp): array;
}
