<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use PDO;

class Mysql implements DatabaseInterface
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

    public function truncateAutoIncrementTable(): void
    {
        $this->connection->query('TRUNCATE TABLE primary_autoincrement');
    }

    public function truncateUuidTable(): void
    {
        $this->connection->query('TRUNCATE TABLE primary_uuid');
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
        $values = '(0x' . implode(
            '),(0x',
            $ids
        ) . ')';

        $query = 'INSERT INTO primary_uuid VALUES ' . $values;

        $startTime = microtime(true);

        $this->connection->query($query);

        $duration = microtime(true) - $startTime;

        return $duration;
    }

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measureSecondaryUuidInsert(array $ids): float
    {
        $values = '(0x' . implode(
                '),(0x',
                $ids
            ) . ')';

        $query = 'INSERT INTO secondary_uuid VALUES ' . $values;

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

    public function getSecondaryUuidIndexSize(): int
    {
        return $this->getIndexSize('secondary_uuid');
    }

    private function getIndexSize(string $tableName): int
    {
        $stmt = $this->connection->query(
            sprintf("
                SELECT stat_value * @@innodb_page_size as index_size
                FROM mysql.innodb_index_stats 
                WHERE 
                    database_name = 'bench' AND 
                    table_name = '%s' AND
                    index_name = 'PRIMARY' AND
                    stat_name = 'size'
                ",
                $tableName
            )
        );

        return (int) $stmt->fetchColumn();
    }
}