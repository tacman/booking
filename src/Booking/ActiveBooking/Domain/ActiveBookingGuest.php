<?php

declare(strict_types=1);


namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\ValueObject\Birthdate;
use Booking\Shared\Domain\ValueObject\CountryCode;
use Booking\Shared\Domain\ValueObject\Date;
use Booking\Shared\Domain\ValueObject\Passport;

final class ActiveBookingGuest
{
    public function __construct(
        public readonly string $name,
        public readonly string $lastname,
        public readonly Birthdate $birthdate,
        public readonly Passport $passport,
        public readonly CountryCode $countryCode
    ) {
    }

    public static function fromPrimitives(
        array $primitives
    ): self {
        return new self(
            $primitives['name'],
            $primitives['lastname'],
            new Birthdate(new Date($primitives['birthdate'])),
            new Passport($primitives['passport']),
            new CountryCode($primitives['country'])
        );
    }

    public function serialize(): array
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'birthdate' => $this->birthdate->value->stringDate(),
            'passport' => $this->passport->value,
            'country' => $this->countryCode->value,
            'age' => $this->birthdate->age(),
        ];
    }
}
