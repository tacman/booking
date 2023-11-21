<?php

namespace Booking\Booking\ActiveBooking\Domain;

use Booking\Shared\Domain\Aggregate\AggregateRoot;
use Booking\Shared\Domain\ValueObject\Locator;
use Booking\Shared\Domain\ValueObject\Room;
use Ramsey\Uuid\Uuid;

final class ActiveBooking extends AggregateRoot
{
    public function __construct(
        public readonly Uuid $bookingId,
        public readonly Uuid $hotel,
        public readonly Locator $locator,
        public readonly Room $room,
        public readonly BookingPeriod $period,
        public readonly BookingTotalPax $totalPax,
        public readonly BookingGuestCollection $guests
    ) {
    }

    public function serialize(): array
    {
        return [
            'bookingId' => $this->bookingId,
            'hotel' => $this->hotel,
            'locator' => $this->locator->value,
            'room' => $this->room,
            'checkIn' => $this->period->checkIn,
            'checkOut' => $this->period->checkOut,
            'numberOfNights' => $this->period->numberOfNights(),
            'totalPax' => $this->locator->value,
            'guests' => $this->guests->serialize(),
        ];
    }
}
