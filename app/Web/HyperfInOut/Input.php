<?php

namespace App\Web\HyperfInOut;

use App\Web\InputInterface;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ServerRequestInterface;

readonly class Input implements InputInterface
{
    #[\Override]
    public function getUrlParameters(ServerRequestInterface $request): array
    {
        $attributes = $request->getAttributes();
        return $attributes[Dispatched::class]->params;
    }

    #[\Override]
    public function getData(ServerRequestInterface $request): array
    {
        return $request->getParsedBody() ?? [];
    }
}
