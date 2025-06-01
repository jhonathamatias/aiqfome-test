<?php

namespace HyperfTest\Unit\Infrastructure\Apis;

use App\Infrastructure\Apis\FakeStoreApi;
use App\Infrastructure\HttpClient\HttpClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FakeStoreApiTest extends TestCase
{
    protected FakeStoreApi $api;

    protected function setUp(): void
    {
        $client = $this->createMock(HttpClientInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $client->method('setBaseUrl');

        $stream->method('getContents')
            ->willReturn(file_get_contents(__DIR__ . '/fixtures/fakeStoreApiMockResponseProduct.json'));

        $response->method('getBody')
            ->willReturn($stream);

        $response->method('getStatusCode')
            ->willReturn(200);

        $client->method('request')
            ->willReturn($response);

        $this->api = new FakeStoreApi($client);
    }

    public function testFetchProduct(): void
    {
        $productId = '1';
        $product = $this->api->fetchProduct($productId);

        $this->assertIsObject($product);
        $this->assertEquals('1', $product->id);
        $this->assertEquals('Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops', $product->title);
        $this->assertEquals(109.95, $product->price);
        $this->assertEquals('https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg', $product->image);
    }

    public function testFetchProductNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to fetch product with ID 999');

        $client = $this->createMock(HttpClientInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getBody')
            ->willReturn($stream);

        $response->method('getStatusCode')
            ->willReturn(404);

        $client->method('request')
            ->willReturn($response);

        $api = new FakeStoreApi($client);
        $api->fetchProduct('999');
    }
}
