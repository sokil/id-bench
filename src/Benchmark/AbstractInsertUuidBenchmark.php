<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Sokil\IdBench\Database\DatabaseInterface;

abstract class AbstractInsertUuidBenchmark implements BenchmarkInterface
{
    public function __construct(
        private readonly DatabaseInterface $database,
    ) {
    }

    abstract protected function generateIds(int $batchSize): array;

    public function createGenerator(
        int $iterations,
        int $batchSize,
    ): \Generator {
        $this->database->truncateUuidTable();

        for ($i = 0; $i < $iterations; $i++) {
            // generate chunk of uuids to insert
            $uuids = $this->generateIds($batchSize);

            // duration of insert
            $duration = $this->database->measureUuidInsert($uuids);


            // size of index
            $indexSize = $this->database->getUuidIndexSize();

            yield [
                'iteration' => $i,
                'duration' => $duration,
                'indexSize' => $indexSize,
            ];
        }
    }
}