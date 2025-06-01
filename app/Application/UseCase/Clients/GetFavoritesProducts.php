<?php

namespace App\Application\UseCase\Clients;

use App\Exception\NotFoundException;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;

class GetFavoritesProducts
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria,
        protected GetClient $getClient,
    ) {
    }

    /**
     * Retrieves the favorite products for a specific client.
     *
     * @param string $clientId The ID of the client whose favorite products are to be retrieved.
     * @param int $limit
     * @return array<object> The list of favorite products for the specified client.
     * @throws NotFoundException
     */
    public function execute(string $clientId, int $limit = 100): array
    {
        $this->getClient->execute($clientId);

        $this->repository->setCollectionName('favorite_products');

        var_dump("Fetching favorite products for client ID: {$clientId} with limit: {$limit}");
        $criteria = clone $this->criteria;
        $criteria->equal('client_id', $clientId);
        $criteria->limit($limit);
        return $this->repository->matching($criteria);
    }
}
