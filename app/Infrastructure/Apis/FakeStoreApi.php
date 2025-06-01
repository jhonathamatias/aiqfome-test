<?php

declare(strict_types=1);

namespace App\Infrastructure\Apis;

use App\Infrastructure\HttpClient\HttpClientInterface;
use RuntimeException;
use stdClass;
use function Hyperf\Support\env;

class FakeStoreApi
{
    public function __construct(protected HttpClientInterface $client)
    {
        $this->client->setBaseUrl(env('FAKE_STORE_API_URL'));
    }

    /**
     * Fetch a product by its ID from the Fake Store API.
     *
     * @param int $id The ID of the product to fetch.
     * @return stdClass{title: string, price: float, description: string, image: string, rating: object{rate: float|null, count: int|null}}|false
     * @throws RuntimeException If the request fails or the product is not found.
     */
    public function fetchProduct(int $id): stdClass|false
    {
        $response = $this->client->request('GET', "/products/{$id}");

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("Failed to fetch product with ID {$id}");
        }

        $data = $response->getBody()->getContents();

        if (empty($data) === true) {
            return false;
        }
        return (object)json_decode($data);
    }
}
