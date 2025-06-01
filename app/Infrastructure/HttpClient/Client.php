<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

class Client implements HttpClientInterface
{
    protected string $baseUrl;

    /** @var array<string> */
    protected array $headers = [];

    public function __construct(protected ClientInterface $client)
    {
    }

    /**
     * @param array<string> $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param array<string> $headers
     * @param array<mixed> $options
     * @throws HttpClientException
     */
    public function request(string $method, string $url, array $body = [], array $headers = [], array $options = []): ResponseInterface
    {
        try {
            $headers = [...$this->headers, ...$headers];
            $url = $this->resolveUrl($url);

            return $this->client->request($method, $url, [
                ...$body,
                'headers' => $headers,
                ...$options,
            ]);
        } catch (ClientException $e) {
            throw new HttpClientException($e->getResponse(), $e->getMessage());
        }
    }

    protected function resolveUrl(string $url): string
    {
        if (isset($this->baseUrl) === true) {
            return "{$this->baseUrl}{$url}";
        }
        return $url;
    }
}
