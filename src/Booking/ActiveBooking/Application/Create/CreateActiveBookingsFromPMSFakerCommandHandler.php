<?php

declare(strict_types=1);

namespace Booking\Booking\ActiveBooking\Application\Create;

use Booking\Booking\ActiveBooking\Domain\ActiveBookingRepository;
use Booking\Booking\ActiveBooking\Domain\PMSFaker\PMSFakerRepository;
use Booking\Shared\Domain\Bus\Command\CommandHandler;
use Booking\Shared\Domain\Bus\Event\EventBus;
use function Lambdish\Phunctional\each;

class CreateActiveBookingsFromPMSFakerCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly PMSFakerRepository $PMSFakerRepository,
        private readonly ActiveBookingRepository $activeBookingRepository,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(CreateActiveBookingsFromPMSFakerCommand $command): void
    {
        each(
            $this->createActiveBooking(),
            $this->pmsBookings()
        );
    }

    private function pmsBookings(): array
    {
        $lastActiveBooking = $this->activeBookingRepository->findOneBy([], [
            'created_at' => 'DESC',
        ]);

        return $this->PMSFakerRepository->findAllSinceTimeStamp(
            $lastActiveBooking?->createdAt
        );
    }

    private function createActiveBooking(): callable
    {
        return function ($pmsBooking) {
            $activeBooking = (new FromPMSBookingToActiveBookingTransformer())->transform($pmsBooking);
            $this->activeBookingRepository->save($activeBooking);
            $this->eventBus->dispatch(...$activeBooking->pullDomainEvents());
        };
    }
}
