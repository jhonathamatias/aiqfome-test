<?php

namespace App\Web;

use Psr\Http\Message\ResponseInterface;

interface OutputInterface
{
    /**
     * @param ResponseInterface $response
     * @param int $status
     * @param array<int|string, mixed>|object|string $body
     * @return ResponseInterface
     */
    public function getResponse(
        ResponseInterface $response,
        int $status = 200,
        array|object|string $body = ''
    ): ResponseInterface;

    /**
     * @param ResponseInterface $response
     * @param int $status
     * @param string $errorMessage
     * @param array<int|string, mixed>|object|string $errorDetails
     * @return ResponseInterface
     */
    public function getResponseError(
        ResponseInterface $response,
        int $status = 400,
        string $errorMessage = '',
        array|object|string $errorDetails = ''
    ): ResponseInterface;
}
