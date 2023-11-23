<?php

declare(strict_types=1);

namespace Booking\Shared\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122101731_create_table_active_booking extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration to create active booking table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('active_booking');
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

    public function down(Schema $schema): void
    {
        $schema->dropTable('active_booking');
    }
}
