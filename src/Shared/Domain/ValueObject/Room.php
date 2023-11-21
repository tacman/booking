<?php

namespace Booking\Shared\Domain\ValueObject;

final class Room
{
    public function __construct(
        public readonly string $value
    ) {
    }
}
