<?php

declare(strict_types=1);

namespace Booking\Tests\ActiveBooking\Infrastructure\Database;

use Booking\Booking\ActiveBooking\Domain\ActiveBooking;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use JsonException;

class ActiveBookingTableConnector
{
    private const TABLE_NAME = 'active_booking';

    /**
     * @throws SchemaException
     */
    public static function create(Schema $schema): void
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('booking_id', 'string', [
            'notnull' => true,
            'length' => 36,
        ]);
        $table->addColumn('hotel', 'string', [
            'notnull' => true,
            'length' => 36,
        ]);
        $table->addColumn('locator', 'string', [
            'notnull' => true,
        ]);
        $table->addColumn('room', 'string', [
            'notnull' => true,
        ]);
        $table->addColumn('check_in', 'datetimetz_immutable', [
            'notnull' => true,
        ]);
        $table->addColumn('check_out', 'datetimetz_immutable', [
            'notnull' => true,
        ]);
        $table->addColumn('total_pax', 'integer', [
            'notnull' => true,
        ]);
        $table->addColumn('guests', 'json', [
            'notnull' => true,
        ]);
        $table->addColumn('created_at', 'datetimetz_immutable', [
            'notnull' => true,
        ]);
        $table->setPrimaryKey(['booking_id']);
        $table->addIndex(['hotel', 'room'], 'active_booking_hotel_room_index');
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public static function insert(
        Connection $connection,
        ActiveBooking $activeBooking
    ): void {
        $connection->insert(self::TABLE_NAME, [
            'booking_id' => $activeBooking->bookingId->value(),
            'hotel' => $activeBooking->hotel->value(),
            'locator' => $activeBooking->locator->value,
            'room' => $activeBooking->room->value,
            'check_in' => $activeBooking->period->checkIn->stringDateTime(),
            'check_out' => $activeBooking->period->checkOut->stringDateTime(),
            'total_pax' => $activeBooking->totalPax->value,
            'guests' => json_encode($activeBooking->guests->serialize(), JSON_THROW_ON_ERROR),
            'created_at' => $activeBooking->createdAt->stringDateTime(),
        ]);
    }

    /**
     * @return array<string,mixed>
     * @throws JsonException
     */
    public static function databaseAssociativeResult(
        ActiveBooking $activeBooking
    ): array {
        return [
            'booking_id' => $activeBooking->bookingId->value(),
            'hotel' => $activeBooking->hotel->value(),
            'locator' => $activeBooking->locator->value,
            'room' => $activeBooking->room->value,
            'check_in' => $activeBooking->period->checkIn->stringDateTime(),
            'check_out' => $activeBooking->period->checkOut->stringDateTime(),
            'total_pax' => $activeBooking->totalPax->value,
            'guests' => json_encode($activeBooking->guests->serialize(), JSON_THROW_ON_ERROR),
            'created_at' => $activeBooking->createdAt->stringDateTime(),
        ];
    }
}
