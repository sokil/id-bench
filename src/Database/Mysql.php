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
        $this->connection->query('TRUNCATE TABLE test_autoincrement');
    }

    public function truncateUuidTable(): void
    {
        $this->connection->query('TRUNCATE TABLE test_uuid');
    }

    public function insertWithAutoIncrement(int $batchSize): void
    {
        $values = implode(
            ',',
            array_fill(0, $batchSize, '(default)')
        );

        $this->connection->query('INSERT INTO test_autoincrement VALUES ' . $values);
    }

    public function insertWithUuid(): void
    {

    }

    public function getAutoIncrementIndexSize(): int
    {
        return $this->getIndexSize('test_autoincrement');
    }

    public function getUuidIndexSize(): int
    {
        return $this->getIndexSize('test_uuid');
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