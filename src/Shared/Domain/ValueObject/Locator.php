<?php

namespace Booking\Shared\Domain\ValueObject;

final class Locator
{
    public function __construct(
        public readonly string $value
    ) {
    }
}
