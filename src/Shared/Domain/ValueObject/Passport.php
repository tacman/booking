<?php

namespace Booking\Shared\Domain\ValueObject;

final class Passport
{
    public function __construct(
        public readonly string $value
    ) {
    }
}
