<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use Ramsey\Uuid\Uuid;

interface DatabaseInterface
{
    public function truncateAutoIncrementTable(): void;

    public function truncateUuidTable(): void;

    public function insertWithAutoIncrement(int $batchSize): void;

    public function insertWithUuid(): void;

    public function getAutoIncrementIndexSize(): int;

    public function getUuidIndexSize(): int;
}