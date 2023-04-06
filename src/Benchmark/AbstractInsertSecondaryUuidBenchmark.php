<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Sokil\IdBench\Database\DatabaseInterface;

abstract class AbstractInsertSecondaryUuidBenchmark implements BenchmarkInterface
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
        $this->database->truncateSecondaryUuidTable();

        for ($i = 0; $i < $iterations; $i++) {
            // generate chunk of uuids to insert
            $uuids = $this->generateIds($batchSize);

            // duration of insert
            $duration = $this->database->measureSecondaryUuidInsert($uuids);


            // size of index
            $indexSize = $this->database->getSecondaryUuidIndexSize();

            yield [
                'iteration' => $i,
                'duration' => $duration,
                'indexSize' => $indexSize,
            ];
        }
    }
}