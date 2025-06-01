<?php

namespace App\Application\DependencyInjection;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Psr\Container\ContainerInterface;
use function Hyperf\Support\env;

class JWTConfigurationFactory
{
    public function __invoke(ContainerInterface $container): Configuration
    {
        return Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::file(env('PRIVATE_PEM')),
            InMemory::file(env('PUBLIC_PEM'))
        );
    }
}
