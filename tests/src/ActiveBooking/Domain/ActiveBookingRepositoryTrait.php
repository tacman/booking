<?php

declare(strict_types=1);

namespace Booking\Tests\ActiveBooking\Domain;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Domain\ActiveBookingRepository;
use PHPUnit\Framework\MockObject\MockObject;

trait ActiveBookingRepositoryTrait
{
    private mixed $repository;

    public function shouldCallFindOneBy(array $fields, array $order, ?ActiveBooking $activeBooking): void
    {
        $this->repository()
            ->expects(self::once())
            ->method('findOneBy')
            ->with($fields, $order)
            ->willReturn($activeBooking);
    }

    public function shouldCallSave(ActiveBooking $activeBookingExpected): void
    {
        $this->repository()
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(
                static function (ActiveBooking $activeBooking) use ($activeBookingExpected): bool {
                    self::assertEquals($activeBooking->hotel, $activeBookingExpected->hotel);
                    self::assertEquals($activeBooking->locator, $activeBookingExpected->locator);
                    self::assertEquals($activeBooking->room, $activeBookingExpected->room);
                    self::assertEquals($activeBooking->period, $activeBookingExpected->period);
                    self::assertEquals($activeBooking->totalPax, $activeBookingExpected->totalPax);
                    self::assertEquals($activeBooking->guests, $activeBookingExpected->guests);
                    return true;
                }
            ));
    }

    public function shouldNotCallSave(): void
    {
        $this->repository()
            ->expects(self::never())
            ->method('save');
    }

    public function repository(): MockObject&ActiveBookingRepository
    {
        return $this->repository = $this->repository ?? $this->createMock(ActiveBookingRepository::class);
    }
}
