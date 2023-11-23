<?php

declare(strict_types=1);

namespace Booking\Shared\Domain\ValueObject;

final class CountryCode
{
    public function __construct(
        public readonly string $value
    ) {
    }
}
