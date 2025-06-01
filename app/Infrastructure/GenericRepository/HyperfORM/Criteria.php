<?php

namespace App\Infrastructure\GenericRepository\HyperfORM;

use App\Infrastructure\GenericRepository\CriteriaInterface;

class Criteria implements CriteriaInterface
{
    /**
     * @var array<array<int, bool|float|int|string|null>>
     */
    protected array $criteriaList = [];

    public function clear(): void
    {
        $this->criteriaList = [];
    }

    /**
     * @return array<array<int, bool|float|int|string|null>>
     */
    #[\Override]
    public function getCriteriaList(): array
    {
        return $this->criteriaList;
    }

    #[\Override]
    public function equal(string $field, string $value): void
    {
        $this->criteriaList[] = [ 'equal', $field, $value ];
    }

    #[\Override]
    public function greater(string $field, string $value): void
    {
        $this->criteriaList[] = [ 'greater', $field, $value ];
    }

    #[\Override]
    public function lower(string $field, string $value): void
    {
        $this->criteriaList[] = [ 'lower', $field, $value ];
    }

    #[\Override]
    public function greaterEqual(string $field, string $value): void
    {
        $this->criteriaList[] = [ 'greaterEqual', $field, $value ];
    }

    #[\Override]
    public function lowerEqual(string $field, string $value): void
    {
        $this->criteriaList[] = [ 'lowerEqual', $field, $value ];
    }

    #[\Override]
    public function orderBy(string $field, string $mode = ''): void
    {
        $this->criteriaList[] = [ 'orderBy', $field, $mode ];
    }

    #[\Override]
    public function limit(int $limit): void
    {
        $this->criteriaList[] = [ 'limit', null, $limit ];
    }

    #[\Override]
    public function offset(int $offset): void
    {
        $this->criteriaList[] = [ 'offset', null, $offset ];
    }
}
