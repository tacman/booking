<?php

declare(strict_types=1);

namespace Booking\App\Command;

use Booking\Booking\ActiveBooking\Application\Create\CreateActiveBookingsFromPMSFakerCommand;
use Booking\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateActiveBookingsFromPmsCommand extends Command
{
    protected static $defaultName = 'create-active-bookings-from-pms';

    private CommandBus $bus;

    public function __construct(CommandBus $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this->setDescription('Store active bookings from pms');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bus->dispatch(new CreateActiveBookingsFromPMSFakerCommand());
        return self::SUCCESS;
    }
}
