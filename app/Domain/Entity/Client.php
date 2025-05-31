<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Domain\ValueObjects\Email;

class Client
{
    public ?string $id = null;

    public string $name;

    public Email $email;

    public function canBeSave(): bool
    {
        if ($this->id !== null) {
            throw new AlreadyExistsException('Client already exists');
        }
        return true;
    }
}
