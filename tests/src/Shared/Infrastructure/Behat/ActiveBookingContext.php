<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\Behat;

use Behat\Behat\Context\Context;
use Doctrine\DBAL\Connection;

final class ActiveBookingContext implements Context
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    /**
     * @BeforeScenario
     */
    public function prepareDatabse(): void
    {
        $this->clearDatabaseActiveBooking();

        $this->connection->insert('active_booking', [
            'booking_id' => 'd6fd01f3-37bb-44ac-9194-d32ff2e81d12',
            'hotel' => 'd9128faa-fb1b-43fb-8f50-a192e290983b',
            'locator' => '655B479CECEAF',
            'room' => '299',
            'check_in' => '2023-11-20',
            'check_out' => '2023-12-03',
            'total_pax' => 1,
            'guests' => '[{"name":"Asier","lastname":"Chapa","birthdate":"1945-08-06","passport":"NP-1320834-ZS","country":"NP","age":78}]',
            'created_at' => '2022-11-20',
        ]);
    }

    /**
     * @AfterScenario
     */
    public function clearDatabse(): void
    {
        $this->clearDatabaseActiveBooking();
    }

    private function clearDatabaseActiveBooking(): void
    {
        $this->connection->delete('active_booking', [
            'booking_id' => 'd6fd01f3-37bb-44ac-9194-d32ff2e81d12',
        ]);
    }
}
