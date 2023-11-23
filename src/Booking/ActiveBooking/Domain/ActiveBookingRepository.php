<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Domain;

interface ActiveBookingRepository
{
    public function findOneBy(array $fields, array $order = []): ?ActiveBooking;

    public function save(ActiveBooking $activeBooking): void;
}
