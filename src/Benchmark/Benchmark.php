<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Sokil\IdBench\Database\DatabaseInterface;

class Benchmark
{
    public function __construct(
        private readonly DatabaseInterface $database
    ) {
    }

    public function run(int $iterations): \Generator
    {
        for ($i = 0; $i < $iterations; $i++) {
            // duration of insert
            $startTime = microtime(true);
            $this->database->insert();
            $duration = microtime(true) - $startTime;

            // size of index
            $indexSize = $this->database->getIndexSize();

            yield ['iteration' => $i, 'duration' => $duration, 'indexSize' => $indexSize];
        }
    }
}