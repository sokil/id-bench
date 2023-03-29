<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Sokil\IdBench\Database\DatabaseInterface;

class InsertAutoIncrementBenchmark implements BenchmarkInterface
{
    public function __construct(
        private readonly DatabaseInterface $database,
    ) {
    }

    public function createGenerator(
        int $iterations,
        int $batchSize,
    ): \Generator {
        $this->database->truncateAutoIncrementTable();

        for ($i = 0; $i < $iterations; $i++) {
            // duration of insert
            $duration = $this->database->measureAutoIncrementInsert($batchSize);

            // size of index
            $indexSize = $this->database->getAutoIncrementIndexSize();

            yield [
                'iteration' => $i,
                'duration' => $duration,
                'indexSize' => $indexSize,
            ];
        }
    }
}