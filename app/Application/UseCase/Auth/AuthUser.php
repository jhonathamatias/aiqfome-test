<?php

namespace App\Application\UseCase\Auth;

use App\Exception\AuthFailedException;
use App\Exception\NotFoundException;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;

class AuthUser
{
    public function __construct(
        protected GenericRepositoryInterface $repository,
        protected CriteriaInterface $criteria,
        protected ClientToken $clientToken
    ) {
    }
    
    public function execute(string $email, string $password): string
    {
        $this->repository->setCollectionName('users');

        $criteria = clone $this->criteria;
        $criteria->equal('email', $email);

        /** @var object{id: string, email: string, password: string}|null $user */
        $user = $this->repository->matching($criteria)[0] ?? null;

        if (null === $user) {
            throw new AuthFailedException('Email and/or password is incorrect');
        }

        if (password_verify($password, $user->password) === false) {
            throw new AuthFailedException('Email and/or password is incorrect');
        }

        return $this->clientToken->execute($user->id);
    }
}
