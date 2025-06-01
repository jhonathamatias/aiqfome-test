<?php

namespace App\Application\UseCase\Clients;

use App\Infrastructure\Apis\FakeStoreApi;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;

class AddFavoriteProduct
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria,
        protected GetClient $getClient,
        protected FakeStoreApi $api
    ) {
    }
    
    public function execute(string $clientId, int $productId): object|false
    {
        $this->getClient->execute($clientId);

        $this->repository->setCollectionName('favorite_products');

        $criteria = clone $this->criteria;
        $criteria->equal('client_id', $clientId);
        $criteria->equal('product_id', (string)$productId);
        $existingProduct = $this->repository->matching($criteria)[0] ?? null;

        if ($existingProduct !== null) {
            throw new \RuntimeException("Product with ID {$productId} already exists in favorites.");
        }

        $product = $this->api->fetchProduct($productId);

        $this->repository->save((object)[
            'client_id' => $clientId,
            'product_id' => $productId,
            'title' => $product->title,
            'price' => $product->price,
            'description' => $product->description,
            'image' => $product->image,
            'rating_rate' => $product->rating->rate ?? null,
            'rating_count' => $product->rating->count ?? null
        ]);

        $result = $this->repository->getById($this->repository->getInsertedLastId());

        if ($result === false) {
            throw new \Exception('Failed add favorite product');
        }
        return $result;
    }
}
