<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Ramsey\Uuid\Uuid;

class InsertPrimaryUuidv4Benchmark extends AbstractInsertPrimaryUuidBenchmark
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