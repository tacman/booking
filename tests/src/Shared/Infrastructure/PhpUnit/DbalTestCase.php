<?php

declare(strict_types=1);

namespace Booking\Tests\Shared\Infrastructure\PhpUnit;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;

abstract class DbalTestCase extends UnitTestCase
{
    protected Connection $connection;

    protected function setUp(): void
    {
        $this->setConnection();

        $schema = new Schema();
        $this->createTables($schema);

        $platform = $this->connection->getDatabasePlatform();
        $queries = $schema->toSql($platform);

        foreach ($queries as $query) {
            $this->connection->fetchAllAssociative($query);
        }
    }

    protected function setConnection(): void
    {
        $connectionParams = [
            'dbname' => 'TEST',
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $this->connection = DriverManager::getConnection($connectionParams);
    }

    abstract protected function createTables(Schema $schema): void;

    /**
     * @throws Exception
     *
     * @return array<mixed>
     */
    protected function fetchAll(string $tableName): array
    {
        return $this->connection->executeQuery("SELECT * from {$tableName}")->fetchAllAssociative();
    }
}
