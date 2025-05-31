<?php

declare(strict_types=1);

namespace App\Application\UseCase\Clients;

use App\Domain\Entity\Client;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Domain\ValueObjects\Email;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use Exception;

class CreateClient
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria
    ) {
        $this->repository->setCollectionName('clients');
    }

    /**
     * Create a new client.
     * @throws AlreadyExistsException
     * @throws Exception
     */
    public function execute(string $name, string $email): object
    {
        $clientAlreadyExists = $this->getClientWithEmail($email);

        $client = new Client();
        $client->id = $clientAlreadyExists->id ?? null;
        $client->name = $name;
        $client->email = new Email($email);

        $client->canBeSave();

        $this->repository->save((object)[
            'name' => $client->name,
            'email' => $client->email,
        ]);

        $result = $this->repository->getById($this->repository->getInsertedLastId());

        if ($result === false) {
            throw new Exception('Failed to create client');
        }
        return $result;
    }

    protected function getClientWithEmail(string $email): object|false
    {
        $this->criteria->equal('email', $email);
        return $this->repository->matching($this->criteria)[0] ?? false;
    }
}
