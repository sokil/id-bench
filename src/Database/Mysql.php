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

    public function getIndexSize(): int
    {
        return 0;
    }
}