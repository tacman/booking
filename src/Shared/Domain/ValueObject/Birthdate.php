<?php

declare(strict_types=1);

namespace Booking\Shared\Domain\ValueObject;

final class Birthdate
{
    private int $age;

    public function __construct(
        public readonly Date $value
    ) {
        $this->calculateAge(
            $this->value
        );
    }

    public function age(): int
    {
        return $this->age;
    }

    private function calculateAge(Date $birthdate): void
    {
        $this->age = $birthdate->diffInYears(new Date());
    }
}
