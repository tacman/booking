<?php

declare(strict_types=1);

namespace Booking\Tests\ActiveBooking\Domain;

use Booking\Booking\ActiveBooking\Domain\PMSFaker\PMSFakerRepository;
use PHPUnit\Framework\MockObject\MockObject;

trait PMSRepositoryTrait
{
    private mixed $pmsRepository;

    public function shouldCallFindAllFromTimeStamp(int $timestamp, array $bookings = []): void
    {
        $this->pmsRepository()
            ->expects(self::once())
            ->method('findAllFromTimeStamp')
            ->with($timestamp)
            ->willReturn($bookings);
    }

    public function pmsRepository(): MockObject&PMSFakerRepository
    {
        return $this->pmsRepository = $this->pmsRepository ?? $this->createMock(PMSFakerRepository::class);
    }
}
