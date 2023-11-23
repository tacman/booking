<?php

namespace Booking\Booking\ActiveBooking\Application\Find;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingNotFound;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingRepository;
use Booking\Shared\Domain\Bus\Query\QueryHandler;

final class FindActiveBookingByHotelAndRoomQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly ActiveBookingRepository $repository
    ) {
    }

    /**
     * @throws ActiveBookingNotFound
     */
    public function __invoke(FindActiveBookingByHotelAndRoomQuery $query): ActiveBookingResponse
    {
        $activeBooking = $this->repository->findOneBy([
            'hotel' => $query->hotel,
            'room' => $query->room,
        ]);

        if ($activeBooking === null) {
            throw new ActiveBookingNotFound(
                sprintf(
                    'Active booking for hotel %s and room %s not found',
                    $query->hotel,
                    $query->room
                )
            );
        }

        return $this->toResponse($activeBooking);
    }

    private function toResponse(ActiveBooking $activeBooking): ActiveBookingResponse
    {
        return new ActiveBookingResponse(
            $activeBooking->bookingId->value(),
            $activeBooking->hotel->value(),
            $activeBooking->locator->value,
            $activeBooking->room->value,
            $activeBooking->period->checkIn->stringDate(),
            $activeBooking->period->checkOut->stringDate(),
            $activeBooking->period->numberOfNights(),
            $activeBooking->totalPax->value,
            $activeBooking->guests->serialize()
        );
    }
}
