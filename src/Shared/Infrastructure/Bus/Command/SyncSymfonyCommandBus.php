<?php

declare(strict_types=1);

namespace Booking\Shared\Infrastructure\Bus\Command;

use Booking\Shared\Domain\Bus\Command\Command;
use Booking\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class SyncSymfonyCommandBus implements CommandBus
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->messageBus = $bus;
    }

    public function dispatch(Command $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
