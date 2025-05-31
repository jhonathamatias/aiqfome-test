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
    protected Model $model;

    protected string $lastInsertedId;

    /**
     * @template T of Model
     * @param class-string<T> $collectionName
     */
    public function setCollectionName(string $collectionName): void
    {
        $className = Str::singular($collectionName);
        $collectionStudly = Str::studly($className);
        $classResolution = "\\App\\Model\\{$collectionStudly}";

        /**
         * @var Model $model
         */
        $model = new $classResolution();

        $this->model = $model;
    }

    #[Override]
    public function getById(int|string $id): false|object
    {
        /**
         * @phpstan-ignore-next-line
         */
        $result = $this->model->find($id);
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
        $id = (string) Uuid::uuid4();
        $entity->id = $id;

        $this->model->fill((array) $entity);
        $this->model->save();

        $this->lastInsertedId = $id;
    }

    #[Override]
    public function delete(int|string $id): void
    {
        // TODO: Implement delete() method.
    }

    #[Override]
    public function matching(CriteriaInterface $criteria): array
    {
        $builder = $this->model;
        foreach ($criteria->getCriteriaList() as $criteriaItem) {
            [$method, $params] = $this->translateQuery($criteriaItem);
            $builder = $builder->{$method}(...$params);
        }
        return (array) $builder
            ->get()
            ->getIterator();
    }

    public function getInsertedLastId(): string
    {
        return $this->lastInsertedId;
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
