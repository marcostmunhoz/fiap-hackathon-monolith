<?php

namespace App\Shared\Infrastructure\Repository;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

abstract class AbstractQueryBuilderRepository
{
    public function __construct(
        private readonly ConnectionInterface $connection
    ) {
    }

    abstract protected function getTable(): string;

    protected function prepareQuery(): Builder
    {
        return $this->connection->table($this->getTable());
    }

    protected function entityExistsWithId(string $id): bool
    {
        return $this->prepareQuery()
            ->where('id', $id)
            ->exists();
    }
}