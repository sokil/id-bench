<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use PDO;

class Postgres implements DatabaseInterface
{
    private readonly PDO $connection;

    public function __construct(
        private readonly string $dsn
    ) {
        $this->connection = new PDO(
            $this->dsn,
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    public function truncatePrimaryAutoIncrementTable(): void
    {
        $this->connection->query('TRUNCATE TABLE primary_autoincrement');
    }

    public function truncatePrimaryUuidTable(): void
    {
        $this->connection->query('TRUNCATE TABLE primary_uuid');
    }

    public function truncateSecondaryUuidTable(): void
    {
        $this->connection->query('TRUNCATE TABLE secondary_uuid');
    }

    public function measurePrimaryAutoIncrementInsert(int $batchSize): float
    {
        $values = implode(
            ',',
            array_fill(0, $batchSize, '(default)')
        );

        $startTime = microtime(true);

        $this->connection->query('INSERT INTO primary_autoincrement VALUES ' . $values);

        $duration = microtime(true) - $startTime;

        return $duration;
    }

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measurePrimaryUuidInsert(array $ids): float
    {
        $values = '(\'' . implode('\'),(\'', $ids) . '\')';

        $query = 'INSERT INTO primary_uuid VALUES ' . $values;

        $startTime = microtime(true);

        $this->connection->query($query);

        $duration = microtime(true) - $startTime;

        return $duration;
    }

    public function getPrimaryAutoIncrementIndexSize(): int
    {
        return $this->getIndexSize('primary_autoincrement');
    }

    public function getPrimaryUuidIndexSize(): int
    {
        return $this->getIndexSize('primary_uuid');
    }

    private function getIndexSize(string $tableName): int
    {
        $stmt = $this->connection->query(
            sprintf("select pg_indexes_size('%s'::regclass)", $tableName)
        );

        return (int) $stmt->fetchColumn();
    }
}