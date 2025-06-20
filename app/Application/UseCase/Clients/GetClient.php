<?php

declare(strict_types=1);

namespace App\Application\UseCase\Clients;

use App\Exception\NotFoundException;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;

class GetClient
{
    public function __construct(
        protected GenericRepositoryInterface $repository
    ) {
    }


    public function execute(string $id): object
    {
        $this->repository->setCollectionName('clients');

        $result = $this->repository->getById($id);

        if (false === $result) {
            throw new NotFoundException("Client with ID $id not found.");
        }
        return $result;
    }
}
