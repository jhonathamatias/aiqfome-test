<?php

namespace App\Infrastructure\SpecificRepository\FakeStore;

use App\Infrastructure\Apis\FakeStoreApi;
use App\Infrastructure\SpecificRepository\ProductsRepositoryInterface;
use Psr\SimpleCache\CacheInterface;

class ProductsRepository implements ProductsRepositoryInterface
{
    public function __construct(
        protected CacheInterface $cache,
        protected FakeStoreApi $api
    ) {
    }

    public function get(int|string $id): object|false
    {
        $cacheKey = "product_$id";
        /** @var object|false $product */
        $product = $this->cache->get($cacheKey) ?? false;

        if (false !== $product) {
            return $product;
        }

        $product = $this->api->fetchProduct((int)$id);
        $this->cache->set($cacheKey, $product, 86400); // TTL um dia (86400 segundos)

        return $product;
    }
}
