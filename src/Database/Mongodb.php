<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use MongoDB\BSON\Binary;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Exception\CommandException;

/**
 * In MongoDB autoincrement is not supported so we just insert integers as ids
 */
class Mongodb implements DatabaseInterface
{
    private const DATABASE_NAME = 'bench';

    private readonly Manager $connection;

    private $autoincrementId = 0;

    public function __construct(
        private readonly string $dsn
    ) {
        $this->connection = new Manager($this->dsn);
    }

    private function truncateTable(string $tableName): void
    {
        try {
            $this->connection->executeCommand(
                self::DATABASE_NAME,
                new Command(['drop' => $tableName])
            );
        } catch (CommandException $e) {
            if ($e->getCode() === 26) {
                // collection not found
                return;
            } else {
                throw $e;
            }
        }
    }

    public function truncatePrimaryAutoIncrementTable(): void
    {
        $this->truncateTable('primary_autoincrement');
    }

    public function truncatePrimaryUuidTable(): void
    {
        $this->truncateTable('primary_uuid');
    }

    public function truncateSecondaryUuidTable(): void
    {
        $this->truncateTable('secondary_uuid');
    }

    public function measurePrimaryAutoIncrementInsert(int $batchSize): float
    {
        $bulk = new BulkWrite();
        for ($i = 0; $i < $batchSize; $i++) {
            $bulk->insert(['_id' => $this->autoincrementId++]);
        }

        $startTime = microtime(true);

        $this->connection->executeBulkWrite(self::DATABASE_NAME . '.primary_autoincrement', $bulk);

        $duration = microtime(true) - $startTime;

        return $duration;
    }

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measurePrimaryUuidInsert(array $ids): float
    {
        $bulk = new BulkWrite();
        for ($i = 0; $i < count($ids); $i++) {
            $bulk->insert(['_id' => new Binary(hex2bin($ids[$i]), Binary::TYPE_UUID)]);
        }

        $startTime = microtime(true);

        $this->connection->executeBulkWrite(self::DATABASE_NAME . '.primary_uuid', $bulk);

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
        $command = [
            "collStats" => $tableName,
        ];

        $result = $this->connection
            ->executeCommand(
                self::DATABASE_NAME,
                new Command($command)
            );

        return $result->toArray()[0]->totalIndexSize;
    }
}