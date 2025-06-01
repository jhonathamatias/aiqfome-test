<?php

namespace App\Application\UseCase\Products;

use App\Exception\NotFoundException;
use App\Infrastructure\SpecificRepository\ProductsRepositoryInterface;

class GetProduct
{
    public function __construct(
        protected ProductsRepositoryInterface $productsRepository
    ) {
    }

    public function execute(int $productId): object
    {
        $product = $this->productsRepository->get($productId);

        if ($product === false) {
            throw new NotFoundException("Product with ID {$productId} not found.");
        }

        return $product;
    }
}
