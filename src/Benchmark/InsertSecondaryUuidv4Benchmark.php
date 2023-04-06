<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Ramsey\Uuid\Uuid;

class InsertSecondaryUuidv4Benchmark extends AbstractInsertSecondaryUuidBenchmark
{
    protected function generateIds(int $batchSize): array
    {
        $uuids = [];

        for ($i = 0; $i < $batchSize; $i++) {
            $uuids[] = Uuid::uuid4()->getHex()->toString();
        }

        return $uuids;
    }
}