<?php

declare(strict_types=1);

namespace App\Infrastructure\HttpClient;

use Exception;
use Psr\Http\Message\ResponseInterface;

class HttpClientException extends Exception
{
    public function __construct(protected ResponseInterface $response, string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
