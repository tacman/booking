<?php

namespace Booking\Shared\Domain\ValueObject;

final class CountryCode
{
    public function __construct(
        public readonly string $value
    ) {
    }
}
