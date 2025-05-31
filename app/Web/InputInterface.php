<?php

namespace App\Web;

use Psr\Http\Message\ServerRequestInterface;

interface InputInterface
{
    /**
     * @return array<string, string>
     */
    public function getUrlParameters(ServerRequestInterface $request): array;

    /**
     * Returns the parsed body of the request.
     *
     * @param ServerRequestInterface $request
     * @return array<string, mixed>
     */
    public function getData(ServerRequestInterface $request): array;
}
