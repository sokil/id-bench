<?php

namespace Sokil\IdBench\Benchmark;

interface BenchmarkInterface
{
    public function createGenerator(int $iterations, int $batchSize): \Generator;
}