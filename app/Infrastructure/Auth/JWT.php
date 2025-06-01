<?php

namespace App\Infrastructure\Auth;

use App\Infrastructure\Auth\Exceptions\CreateTokenErrorException;
use App\Infrastructure\Auth\Exceptions\InvalidJWTException;
use App\Infrastructure\Auth\Interfaces\JWTInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Psr\Clock\ClockInterface;

class JWT implements JWTInterface
{
    protected BearerToken $token;
    protected object $data;

    public function __construct(
        protected Configuration $configuration,
        protected ClockInterface $clock
    ) {
    }

    public function setToken(BearerToken $token): void
    {
        $this->token = $token;
    }

    public function setData(object $data): void
    {
        $this->data = $data;
    }

    public function getToken(): string
    {
        return (string)$this->token;
    }

    public function createToken(): string
    {
        try {
            $now = $this->clock->now();

            $builder = $this->configuration
                ->builder()
                ->expiresAt($now->modify('+3 days'))
                ->issuedAt($now)
                ->canOnlyBeUsedAfter($now);

            if (isset($this->data)) {
                foreach ($this->data as $key => $value) {
                    $builder = $builder->withClaim($key, $value);
                }
            }

            return $builder
                ->getToken($this->configuration->signer(), $this->configuration->signingKey())
                ->toString();
        } catch (\Exception) {
            throw new CreateTokenErrorException('Create token error');
        }
    }

    public function validate(): bool
    {
        try {
            $parser    = $this->configuration->parser();
            $validator = $this->configuration->validator();
            $token     = $parser->parse($this->getToken());

            $validator->assert($token, new SignedWith(
                $this->configuration->signer(),
                $this->configuration->verificationKey()
            ), new StrictValidAt($this->clock));

            $data = (object)$token->claims()->all();

            $this->setData($data);

            return true;
        } catch (\Exception) {
            throw new InvalidJWTException('Invalid token');
        }
    }

    public function getData(): object|null
    {
        if (true === isset($this->data)) {
            return $this->data;
        }
        return null;
    }
}
