<?php

declare(strict_types=1);

namespace Sokil\IdBench\Database;

interface DatabaseInterface
{
    public function insert(): void;

    public function getIndexSize(): int;
}