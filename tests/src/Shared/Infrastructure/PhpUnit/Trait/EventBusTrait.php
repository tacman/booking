<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\PhpUnit\Trait;

use Booking\Shared\Domain\Bus\Event\DomainEvent;
use Booking\Shared\Domain\Bus\Event\EventBus;
use PHPUnit\Framework\MockObject\MockObject;

trait EventBusTrait
{
    private mixed $eventBus;

    public function shouldCallDispatch(): void
    {
        $this->eventBus()
            ->expects(self::once())
            ->method('dispatch');
    }

    public function shouldNotCallDispatch(): void
    {
        $this->eventBus()
            ->expects(self::never())
            ->method('dispatch');
    }

    public function shouldCallDispatchWithoutEvents(): void
    {
        $this->eventBus()
            ->expects(self::once())
            ->method('dispatch')
            ->with();
    }

    public function shouldCallDispatchEvents(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $callbacks[] = self::callback(function (DomainEvent ...$domainEvents) use ($event) {
                $this->assertSimilar([$event], $domainEvents);

                return true;
            });
        }

        $this->eventBus()
            ->expects(self::once())
            ->method('dispatch')
            ->with(...$callbacks);
    }

    public function eventBus(): MockObject&EventBus
    {
        return $this->eventBus = $this->eventBus ?? $this->createMock(EventBus::class);
    }
}
