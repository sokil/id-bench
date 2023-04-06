<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use Ramsey\Uuid\Uuid;

interface DatabaseInterface
{
    public function truncateAutoIncrementTable(): void;

    public function truncateUuidTable(): void;

    public function measurePrimaryAutoIncrementInsert(int $batchSize): float;

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measureSecondaryUuidInsert(array $ids): float;

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measurePrimaryUuidInsert(array $ids): float;

    public function getPrimaryAutoIncrementIndexSize(): int;

    public function getPrimaryUuidIndexSize(): int;

    public function getSecondaryUuidIndexSize(): int;
}