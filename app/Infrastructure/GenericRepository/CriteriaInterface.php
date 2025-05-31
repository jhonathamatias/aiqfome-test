<?php

namespace App\Infrastructure\GenericRepository;

interface CriteriaInterface
{
    /**
     * @return array<int, int|float|string|bool>[]
     */
    public function getCriteriaList(): array;

    public function equal(string $field, string $value): void;

    public function greater(string $field, string $value): void;

    public function lower(string $field, string $value): void;

    public function greaterEqual(string $field, string $value): void;

    public function lowerEqual(string $field, string $value): void;

    public function orderBy(string $field, string $mode = ''): void;

    public function limit(int $limit): void;

    public function offset(int $offset): void;
}