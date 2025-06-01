<?php

namespace App\Infrastructure\SpecificRepository;

interface ProductsRepositoryInterface
{
    public function get(string|int $id): object|false;
}
