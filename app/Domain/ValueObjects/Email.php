<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;
use Respect\Validation\Validator as v;
use Stringable;

class Email implements Stringable
{
    public function __construct(protected string $value)
    {
        if (v::email()->validate($this->value) === false) {
            throw new InvalidArgumentException('Invalid email');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
