<?php

declare(strict_types=1);

namespace Sokil\IdBench\Benchmark;

use Ramsey\Uuid\Uuid;

class InsertUuidv3Benchmark extends AbstractInsertUuidBenchmark
{
    protected function generateIds(int $batchSize): array
    {
        $uuids = [];

        for ($i = 0; $i < $batchSize; $i++) {
            $uuids[] = Uuid::uuid3(
                Uuid::NAMESPACE_OID,
                Uuid::uuid4()->toString()
            )->getHex()->toString();
        }

        return $uuids;
    }
}