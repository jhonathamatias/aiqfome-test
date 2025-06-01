<?php

namespace App\Infrastructure\Auth;

use App\Infrastructure\Auth\Exceptions\InvalidBearerTokenException;
use Stringable;

class BearerToken implements Stringable
{

    public function __construct(protected string $value)
    {
        if (false === $this->validate()) {
            throw new InvalidBearerTokenException('Invalid token');
        }
        $this->value = $this->getToken();
    }

    protected function validate(): bool
    {
        $split = explode(' ', $this->value);

        if (count($split) < 2) {
            return false;
        }
        return str_starts_with($this->value, 'Bearer');
    }

    protected function getToken(): string
    {
        [, $token] = explode(' ', $this->value);
        return $token;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
