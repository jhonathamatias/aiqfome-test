<?php

namespace App\Application\UseCase\Clients;

use Hyperf\Coroutine;

class AddManyFavoritesProducts
{
    public function __construct(
        protected AddFavoriteProduct $addFavoriteProduct
    ) {
    }

    /**
     * @param string $clientId
     * @param array<int> $productIds
     * @return array<object>
     */
    public function execute(string $clientId, array $productIds): array
    {
        $parallel = new Coroutine\Parallel(5);

        foreach ($productIds as $productId) {
            $parallel->add(function () use ($clientId, $productId) {
                Coroutine\Coroutine::sleep(0.1);
                try {
                    return $this->addFavoriteProduct->execute($clientId, $productId);
                } catch (\Exception $e) {
                    return [
                        'product_id' => $productId,
                        'error' => true,
                        'message' => $e->getMessage(),
                    ];
                }
            });
        }

        return $parallel->wait();
    }
}
