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

    public function truncateAutoIncrementTable(): void
    {
        $this->truncateTable('test_autoincrement');
    }

    public function truncateUuidTable(): void
    {
        $this->truncateTable('test_uuid');
    }

    public function measureAutoIncrementInsert(int $batchSize): float
    {
        $bulk = new BulkWrite();
        for ($i = 0; $i < $batchSize; $i++) {
            $bulk->insert(['_id' => $this->autoincrementId++]);
        }

        $startTime = microtime(true);

        $this->connection->executeBulkWrite(self::DATABASE_NAME . '.test_autoincrement', $bulk);

        $duration = microtime(true) - $startTime;

        return $duration;
    }

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measureUuidInsert(array $ids): float
    {
        $bulk = new BulkWrite();
        for ($i = 0; $i < count($ids); $i++) {
            $bulk->insert(['_id' => new Binary(hex2bin($ids[$i]), Binary::TYPE_UUID)]);
        }

        $startTime = microtime(true);

        $this->connection->executeBulkWrite(self::DATABASE_NAME . '.test_uuid', $bulk);

        $duration = microtime(true) - $startTime;

        return $duration;
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