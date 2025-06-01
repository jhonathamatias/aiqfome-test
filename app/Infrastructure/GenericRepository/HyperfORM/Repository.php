<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Infrastructure\GenericRepository\HyperfORM;

use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use Exception;
use Hyperf\Database\Model\Model;
use Hyperf\Stringable\Str;
use Override;
use Ramsey\Uuid\Uuid;

class Repository implements GenericRepositoryInterface
{
    protected string $model;

    protected string $lastInsertedId;

    /**
     * @template T of Model
     * @param class-string<T> $collectionName
     */
    public function setCollectionName(string $collectionName): void
    {
        $className = Str::singular($collectionName);
        $collectionStudly = Str::studly($className);
        $this->model = "\\App\\Model\\{$collectionStudly}";
    }

    #[Override]
    public function getById(int|string $id): false|object
    {
        /** @var Model $model */
        $model = new $this->model();

        /**
         * @phpstan-ignore-next-line
         */
        $result = $model->find($id);
        if ($result === null) {
            return false;
        }
        return $result;
    }

    #[Override]
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
        return [];
    }

    #[Override]
    public function save(object $entity): void
    {
        $id = (string)Uuid::uuid4();

        /** @var Model $model */
        $model = new $this->model();

        $model->fill([
            'id' => $id,
            ...(array)$entity
        ]);
        $model->save();

        $this->lastInsertedId = $id;
    }

    #[Override]
    public function delete(int|string $id): bool
    {
        /** @var Model $model */
        $model = new $this->model();

        /** @phpstan-ignore-next-line */
        $builder =  $model->where('id', '=', $id);
        return (bool)$builder->delete();
    }

    #[Override]
    public function matching(CriteriaInterface $criteria): array
    {
        /** @var Model $model */
        $model = new $this->model();

        $builder = $model;
        foreach ($criteria->getCriteriaList() as $criteriaItem) {
            [$method, $params] = $this->translateQuery($criteriaItem);
            $builder = $builder->{$method}(...$params);
        }
        return (array)$builder
            ->get()
            ->getIterator();
    }

    public function getInsertedLastId(): string
    {
        return $this->lastInsertedId;
    }

    public function update(CriteriaInterface $criteria, array $fields): bool
    {
        /** @var Model $model */
        $model = new $this->model();
        $builder = $model;

        foreach ($criteria->getCriteriaList() as $criteriaItem) {
            [$method, $params] = $this->translateQuery($criteriaItem);
            $builder = $builder->{$method}(...$params);
        }

        return (bool)$builder->update($fields);
    }

    /**
     * @param array<int, null|bool|float|int|string> $criteria
     * @return array<int, array<int, null|bool|float|int|string>|string>
     * @throws Exception
     */
    private function translateQuery(array $criteria): array
    {
        [$filter, $field, $value] = $criteria;

        return match ($filter) {
            'equal' => ['where', [$field, '=', $value]],
            'greater' => ['where', [$field, '>', $value]],
            'greaterEqual' => ['where', [$field, '>=', $value]],
            'lower' => ['where', [$field, '<', $value]],
            'lowerEqual' => ['where', [$field, '<=', $value]],
            'orderBy' => ['orderBy', [$field, $value === '' ? 'ASC' : 'DESC']],
            'limit' => ['take', [$value]],
            'offset' => ['skip', [$value]],
            default => throw new Exception('Criteria not found')
        };
    }
}
