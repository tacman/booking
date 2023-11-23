<?php

namespace Booking\Booking\ActiveBooking\Application\Create;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Shared\Domain\ValueObject\Uuid;

class FromPMSBookingToActiveBookingTransformer
{
    private const HOTEL_ID_MAPPING = [
        '28001' => '3cbcd874-a7e0-4bb3-987e-eb36f05b7e7a',
        '28003' => 'ca385c3b-c2b1-4691-b433-c8cd51883d25',
        '36001' => '70ce8358-600a-4bad-8ee6-acf46e1fb8db',
        '49001' => '5ab1d247-19ea-4850-9242-2d3ffbbdb58d',
    ];

    /**
     * @throws InvalidValueException
     */
    public function transform(array $pmsBooking): ActiveBooking
    {
        return ActiveBooking::create(
            (Uuid::random())->value(),
            $this->fromExternalId($pmsBooking['hotel_id']),
            $pmsBooking['booking']['locator'],
            $pmsBooking['booking']['room'],
            $pmsBooking['booking']['check_in'],
            $pmsBooking['booking']['check_out'],
            $this->totalPax($pmsBooking['booking']['pax']),
            [$pmsBooking['guest']]
        );
    }

    /**
     * @throws InvalidValueException
     */
    private function fromExternalId(string $externalHotelId): string
    {
        return self::HOTEL_ID_MAPPING[$externalHotelId] ??
            throw new InvalidValueException(
                'External pms hotel id not valid'
            );
    }

    private function totalPax(array $numPaxByType): int
    {
        return (int) array_sum($numPaxByType);
    }
}
