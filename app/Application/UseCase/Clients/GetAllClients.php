<?php

declare(strict_types=1);

namespace App\Application\UseCase\Clients;

use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;

class GetAllClients
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria
    ) {
    }

    /**
     * @param int $limit
     * @return array<object>
     */
    public function execute(int $limit = 100): array
    {
        $this->repository->setCollectionName('clients');

        $criteria = clone $this->criteria;
        $criteria->limit($limit);
        return $this->repository->matching($criteria);
    }
}
