<?php

namespace Booking\Tests\ActiveBooking\Domain;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingGuest;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingGuestCollection;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingPeriod;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingTotalPax;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Shared\Domain\ValueObject\Birthdate;
use Booking\Shared\Domain\ValueObject\CountryCode;
use Booking\Shared\Domain\ValueObject\Date;
use Booking\Shared\Domain\ValueObject\Locator;
use Booking\Shared\Domain\ValueObject\Passport;
use Booking\Shared\Domain\ValueObject\Room;
use Booking\Shared\Domain\ValueObject\Uuid;

class ActiveBookingMother
{
    public const UUID = '00000000-0000-0000-0000-000000000000';

    /**
     * @throws InvalidValueException
     */
    public static function random(): ActiveBooking
    {
        return new ActiveBooking(
            new Uuid(self::UUID),
            new Uuid('5ab1d247-19ea-4850-9242-2d3ffbbdb58d'),
            new Locator('randomLocator'),
            new Room('509'),
            new ActiveBookingPeriod(
                new Date(),
                new Date()
            ),
            new ActiveBookingTotalPax(1),
            new ActiveBookingGuestCollection(
                new ActiveBookingGuest(
                    'John',
                    'Doe',
                    new Birthdate((new Date())->modify('-30 years')),
                    new Passport('NL9123115'),
                    new CountryCode('ES')
                )
            ),
            new Date()
        );
    }
}
