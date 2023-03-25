<?php

declare(strict_types=1);

namespace Sokil\Bench;

class InsertMysqlBench
{
    public function provideConnection()
    {
        $pdo = new \PDO(getenv('CONNECTION_DSN'));
        return [
            'connection' => $pdo,
        ];
    }

    /**
     * @ParamProviders({"provideConnection"})
     * @Revs(100)
     * @Iterations(1)
     */
    public function benchAutoincrement(array $params): void
    {

    }

    public function benchUUIDv1(): void
    {

    }

    public function benchUUIDv2(): void
    {

    }

    public function benchUUIDv3(): void
    {

    }

    public function benchUUIDv4(): void
    {

    }

    public function benchUUIDv5(): void
    {

    }

    public function benchUUIDv6(): void
    {

    }

    public function benchUUIDv7(): void
    {

    }
}