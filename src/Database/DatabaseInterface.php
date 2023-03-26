<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

use Ramsey\Uuid\Uuid;

interface DatabaseInterface
{
    public function insertWithAutoIncrement(int $batchSize): void;

    public function insertWithUuid(): void;

    public function getIndexSize(): int;
}