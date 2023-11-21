<?php

declare(strict_types=1);

namespace Booking\Shared\Domain\Bus\Event;

abstract class DomainEvent
{
    private \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    abstract public static function eventName(): string;

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
