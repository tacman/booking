<?php

declare(strict_types=1);

namespace Booking\Shared\Domain\ValueObject;

use Booking\Shared\Domain\Exception\InvalidValueException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    /**
     * @throws InvalidValueException
     */
    public function __construct(
        protected string $value
    ) {
        self::ensureIsValidUuid($value);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    /**
     * @throws InvalidValueException
     */
    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value() === $other->value();
    }

    /**
     * @throws InvalidValueException
     */
    public static function ensureIsValidUuid(string $id): void
    {
        if (! RamseyUuid::isValid($id)) {
            throw new InvalidValueException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }
}
