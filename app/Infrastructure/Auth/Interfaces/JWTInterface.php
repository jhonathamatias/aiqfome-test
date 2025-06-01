<?php

namespace App\Infrastructure\Auth\Interfaces;

use App\Infrastructure\Auth\BearerToken;

interface JWTInterface
{
    public function createToken(): string;
    public function setToken(BearerToken $token): void;
    public function validate(): bool;
    public function setData(object $data): void;
    public function getData(): object|null;
}
