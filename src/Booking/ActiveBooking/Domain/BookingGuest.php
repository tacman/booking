<?php /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */

namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\ValueObject\Birthdate;
use Booking\Shared\Domain\ValueObject\CountryCode;
use Booking\Shared\Domain\ValueObject\Passport;

final class BookingGuest
{
    public function __construct(
        public readonly string $name,
        public readonly string $lastname,
        public readonly Birthdate $birthdate,
        public readonly Passport $passport,
        public readonly CountryCode $countryCode
    ) {
    }

    public function serialize(): array
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'birhdate' => $this->birthdate->value->stringDate(),
            'passport' => $this->passport->value,
            'country' => $this->countryCode->value,
            'age' => $this->birthdate->age(),
        ];
    }
}
