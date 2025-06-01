<?php

declare(strict_types=1);

namespace App\Application\DependencyInjection;

use App\Infrastructure\HttpClient\Client;
use App\Infrastructure\HttpClient\HttpClientInterface;
use Hyperf\Guzzle\ClientFactory;
use Psr\Container\ContainerInterface;

class HttpClientFactoryInvoker
{
    public function __invoke(ContainerInterface $container): HttpClientInterface
    {
        /** @var ClientFactory $client */
        $client = $container->get(ClientFactory::class);
        return new Client($client->create());
    }
}
