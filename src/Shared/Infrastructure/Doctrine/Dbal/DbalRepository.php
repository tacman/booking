<?php

declare(strict_types=1);

namespace Booking\Shared\Infrastructure\Doctrine\Dbal;

use Booking\Shared\Domain\Exception\AlreadyStoredException;
use Booking\Shared\Domain\Exception\InternalErrorException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Throwable;

abstract class DbalRepository
{
    public function __construct(
        public readonly Connection $connection
    ) {
    }

    /**
     * @param array<string,mixed> $data
     *
     * @throws InternalErrorException
     * @throws AlreadyStoredException
     */
    public function insert(array $data): void
    {
        try {
            $this->connection->insert(
                $this->table(),
                $data
            );
        } catch (UniqueConstraintViolationException $e) {
            throw new AlreadyStoredException($e->getMessage());
        } catch (Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    /**
     * @param array<string,mixed> $fields
     * @param array<string,mixed> $orderBy
     *
     * @throws InternalErrorException
     */
    protected function findBy(array $fields, bool $fetchAll = true, array $orderBy = []): mixed
    {
        try {
            $queryBuilder = $this->connection->createQueryBuilder()
                ->select('*')
                ->from($this->table(), 'p');

            foreach ($fields as $field => $value) {
                $queryBuilder->andWhere(sprintf('%1$s = :%1$s', $field));
                $queryBuilder->setParameter($field, $value);
            }

            if (! empty($orderBy)) {
                foreach ($orderBy as $orderByField => $orderByDirection) {
                    $queryBuilder->addOrderBy($orderByField, $orderByDirection);
                }
            }

            if ($fetchAll) {
                return $queryBuilder->fetchAllAssociative();
            }

            return $queryBuilder->fetchAssociative();
        } catch (Throwable $e) {
            throw new InternalErrorException($e->getMessage());
        }
    }

    abstract protected function table(): string;
}
