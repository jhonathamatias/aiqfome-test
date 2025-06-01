<?php

namespace App\Application\UseCase\Clients;

use App\Application\UseCase\Products\GetProduct;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use Psr\SimpleCache\CacheInterface;

class AddFavoriteProduct
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria,
        protected CacheInterface $cache,
        protected GetClient $getClient,
        protected GetProduct $getProduct
    ) {
    }
    
    public function execute(string $clientId, int $productId): object
    {
        var_dump("Adding product {$productId} for client {$clientId}");
        $this->getClient->execute($clientId);

        $this->repository->setCollectionName('favorite_products');

        $criteria = clone $this->criteria;
        $criteria->equal('client_id', $clientId);
        $criteria->equal('product_id', (string)$productId);
        $existingProduct = $this->repository->matching($criteria)[0] ?? null;

        if ($existingProduct !== null) {
            throw new AlreadyExistsException("Product with ID {$productId} already exists in favorites.");
        }

        /** @var object{title: string, price: float, description: string, image: string, rating: object{rate: float, count: int}} $product */
        $product = $this->getProduct->execute($productId);

        $this->repository->save(entity: (object)[
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
