<?php

namespace App\Web;

use Psr\Http\Message\ServerRequestInterface;

interface InputInterface
{
    /**
     * @return array<string, string>
     */
    public function getUrlParameters(ServerRequestInterface $request): array;

    public function getData(ServerRequestInterface $request): array;

}