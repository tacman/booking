<?php

namespace Booking\Tests\ActiveBooking\Infrastructure\Database;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Booking\Booking\ActiveBooking\Infrastructure\Database\DbalActiveBookingRepository;
use Booking\Shared\Domain\Exception\AlreadyStoredException;
use Booking\Shared\Domain\Exception\InternalErrorException;
use Booking\Shared\Domain\Exception\InvalidValueException;
use Booking\Shared\Domain\ValueObject\Date;
use Booking\Tests\ActiveBooking\Domain\ActiveBookingMother;
use Booking\Tests\Shared\Infrastructure\PhpUnit\DbalTestCase;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use function ECSPrefix20211002\Stringy\create;

class DbalActiveBookingRepositoryTest extends DbalTestCase
{
    private const TABLE_NAME = 'active_booking';

    private DbalActiveBookingRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DbalActiveBookingRepository(
            $this->connection,
        );
    }

    /**
     * @throws InvalidValueException
     * @throws InternalErrorException
     * @throws AlreadyStoredException
     * @throws Exception
     * @throws \JsonException
     */
    public function testShouldSave(): void
    {
        $activeBooking = ActiveBookingMother::create()->build();
        $this->repository->save($activeBooking);
        $requests = $this->fetchAll(self::TABLE_NAME);

        self::assertEquals(
            [ActiveBookingTableConnector::databaseAssociativeResult($activeBooking)],
            $requests
        );
    }

    public function testShouldFindOneBy(): void
    {
        $activeBooking = ActiveBookingMother::create()
            ->withCreatedAt((new Date())->modify('-1 hour'))
            ->build();

        $lastActiveBookingInserted = ActiveBookingMother::create()->build();

        $this->insert($activeBooking);
        $this->insert($lastActiveBookingInserted);

        $found = $this->repository->findOneBy(
            [],
            [
                'created_at' => 'DESC',
            ]
        );

        $this->assertEquals($lastActiveBookingInserted->bookingId, $found->bookingId);
    }

    /**
     * @throws SchemaException
     */
    protected function createTables(Schema $schema): void
    {
        ActiveBookingTableConnector::create($schema);
    }

    /**
     * @throws \JsonException
     * @throws Exception
     */
    protected function insert(ActiveBooking $activeBooking): void
    {
        ActiveBookingTableConnector::insert($this->connection, $activeBooking);
    }
}
