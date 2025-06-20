<?php

declare(strict_types=1);

namespace App\Application\UseCase\Clients;

use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use Exception;

class DeleteClient
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected GetClient $getClient
    ) {
    }

    /**
     * Delete a client.
     * @throws Exception
     */
    public function execute(string $id): object
    {
        $this->repository->setCollectionName('clients');

        $client = $this->getClient->execute($id);

        $result = $this->repository->delete($id);

        if ($result === false) {
            throw new Exception('Client not deleted');
        }
        return $client;
    }
}
