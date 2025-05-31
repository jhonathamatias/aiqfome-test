<?php

namespace App\Web\HyperfInOut;

use App\Web\OutputInterface;
use Psr\Http\Message\ResponseInterface;

class JsonOutput implements OutputInterface
{
    public function getResponse(
        ResponseInterface $response,
        int $status = 200,
        array|object|string $body = ''
    ): ResponseInterface {
        $responseData = [
            "data" => $body
        ];
        $response->getBody()->write(json_encode($responseData, JSON_THROW_ON_ERROR, 1024));
        return $response
                    ->withStatus($status)
                    ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param ResponseInterface $response
     * @param int $status
     * @param string $errorMessage
     * @param array<int|string, mixed>|object|string $errorDetails
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function getResponseError(
        ResponseInterface $response,
        int $status = 400,
        string $errorMessage = '',
        array|object|string $errorDetails = ''
    ): ResponseInterface {
        $responseData = [
            'error' => [
                'message' => $errorMessage,
                'error_details' => $errorDetails
            ]
        ];
        $response->getBody()->write(json_encode($responseData, JSON_THROW_ON_ERROR, 1024));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
