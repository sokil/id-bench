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

    public function run(
        int $iterations,
        int $batchSize,
    ): \Generator {
        for ($i = 0; $i < $iterations; $i++) {
            // duration of insert
            $startTime = microtime(true);
            $this->database->insertWithAutoIncrement($batchSize);
            $duration = microtime(true) - $startTime;

            // size of index
            $indexSize = $this->database->getIndexSize();

            yield [
                'iteration' => $i,
                'duration' => $duration,
                'indexSize' => $indexSize,
            ];
        }
    }
}