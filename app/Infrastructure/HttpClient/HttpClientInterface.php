<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function setBaseUrl(string $baseUrl): void;

    /**
     * @param array<string> $headers
     */
    public function setHeaders(array $headers): void;

    /**
     * @param array<mixed> $body
     * @param array<string> $headers
     */
    public function request(string $method, string $url, array $body = [], array $headers = []): ResponseInterface;
}
