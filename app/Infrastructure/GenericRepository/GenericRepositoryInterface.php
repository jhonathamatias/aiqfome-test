<?php

declare(strict_types=1);

namespace App\Infrastructure\GenericRepository;

interface GenericRepositoryInterface
{
    public function setCollectionName(string $collectionName): void;

    public function getById(int|string $id): false|object;

    /**
     * @return object[]
     */
    public function matching(CriteriaInterface $criteria): array;

    /**
     * @return object[]
     */
    public function findAll(): array;

    public function save(object $entity): void;

    public function delete(int|string $id): void;

    public function getInsertedLastId(): string;
}
