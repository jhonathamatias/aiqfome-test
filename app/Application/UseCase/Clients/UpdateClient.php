<?php

declare(strict_types=1);

namespace App\Application\UseCase\Clients;

use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use Exception;

class UpdateClient
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria,
        protected GetClient $getClient
    ) {
    }

    /**
     * Update a new client.
     * @throws Exception
     */
    public function execute(string $id, string|null $name = null, string|null $email = null): bool
    {
        $this->repository->setCollectionName('clients');

        $this->getClient->execute($id);

        $fields = [];

        if ($name !== null) {
            $fields['name'] = $name;
        }

        if ($email !== null) {
            $this->alreadyExistsWithEmail($email);
            $fields['email'] = $email;
        }

        $criteria = clone $this->criteria;
        $criteria->equal('id', $id);

        $result = $this->repository->update($criteria, $fields);

        if ($result === false) {
            throw new Exception('Client not updated');
        }
        return true;
    }

    protected function alreadyExistsWithEmail(string $email): true
    {
        $criteria = clone $this->criteria;
        $criteria->equal('email', $email);

        $result = $this->repository->matching($criteria)[0] ?? false;

        if ($result !== false) {
            throw new AlreadyExistsException('Client with this email already exists');
        }
        return true;
    }
}
