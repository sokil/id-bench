<?php

namespace Sokil\IdBench\Benchmark;

interface BenchmarkInterface
{
    public function run(int $iterations, int $batchSize): \Generator;
}