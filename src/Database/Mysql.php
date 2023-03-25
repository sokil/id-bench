<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

class Mysql implements DatabaseInterface
{
    public function __construct(
        private readonly string $dsn
    ) {
    }

    public function insert(): void
    {

    }

    public function getIndexSize(): int
    {
        return 0;
    }
}