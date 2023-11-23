<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Application\Create;

use Booking\Booking\ActiveBooking\Domain\ActiveBookingRepository;
use Booking\Booking\ActiveBooking\Domain\PMSFaker\PMSFakerRepository;
use Booking\Shared\Domain\Bus\Event\EventBus;
use Booking\Shared\Domain\Exception\InvalidValueException;

class CreateActiveBookingsFromPMSFakerCommandHandler
{
    private const DEFAULT_TIMESTAMP = 0;

    public function __construct(
        private readonly PMSFakerRepository $PMSFakerRepository,
        private readonly ActiveBookingRepository $activeBookingRepository,
        private readonly EventBus $eventBus
    ) {
    }

    /**
     * @throws InvalidValueException
     */
    public function __invoke(CreateActiveBookingsFromPMSFakerCommand $command): void
    {
        $pmsBookings = $this->pmsBookings();

        foreach ($pmsBookings as $pmsBooking) {
            $activeBooking = (new FromPMSBookingToActiveBookingTransformer())->transform($pmsBooking);
            $this->activeBookingRepository->save($activeBooking);
            $this->eventBus->dispatch(...$activeBooking->pullDomainEvents());
        }
    }

    private function pmsBookings(): array
    {
        $lastActiveBooking = $this->activeBookingRepository->findOneBy([], [
            'created_at' => 'DESC',
        ]);

        return $this->PMSFakerRepository->findAllFromTimeStamp(
            $lastActiveBooking !== null ?
                $lastActiveBooking->createdAt->date()->getTimestamp() :
                self::DEFAULT_TIMESTAMP
        );
    }
}
