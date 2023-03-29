<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use Ramsey\Uuid\Uuid;

interface DatabaseInterface
{
    public function truncateAutoIncrementTable(): void;

    public function truncateUuidTable(): void;

    public function measureAutoIncrementInsert(int $batchSize): float;

    /**
     * @param string[] $ids List of uuids in 32 chars hex format
     */
    public function measureUuidInsert(array $ids): float;

    public function getAutoIncrementIndexSize(): int;

    public function getUuidIndexSize(): int;
}