<?php

namespace HyperfTest\Integration\UseCase\Auth;

use App\Application\UseCase\Auth\AuthUser;
use App\Application\UseCase\Auth\ClientToken;
use App\Exception\AuthFailedException;
use App\Infrastructure\Auth\Interfaces\JWTInterface;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthUser::class)]
class AuthUserTest extends TestCase
{
    protected ClientToken $clientToken;
    protected GenericRepositoryInterface $repository;
    protected CriteriaInterface $criteria;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(GenericRepositoryInterface::class);
        $this->criteria = $this->createMock(CriteriaInterface::class);
        $jwt = $this->createMock(JWTInterface::class);

        $this->criteria->method('equal');

        $this->repository->method('setCollectionName');

        $jwt->method('createToken')->willReturn('mocked.jwt.token');

        $this->clientToken = new ClientToken($jwt);
    }

    public function testAuthUserWithValidCredentials(): void
    {
        $this->repository->method('matching')->willReturn([
            (object)[
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'email' => 'admin@admin.com',
                'password' => password_hash('132456', PASSWORD_BCRYPT),
            ]
        ]);
        $authUser = new AuthUser($this->repository, $this->criteria, $this->clientToken);

        $token = $authUser->execute('admin@admin.com', '132456');
        $this->assertEquals('mocked.jwt.token', $token);
    }

    public function testAuthUserWithInvalidEmail(): void
    {
        $this->expectException(AuthFailedException::class);

        $this->repository->method('matching')->willReturn([]);

        $authUser = new AuthUser($this->repository, $this->criteria, $this->clientToken);
        $authUser->execute('admin@admin.com', '132456');
    }

    public function testAuthUserWithInvalidPassword(): void
    {
        $this->expectException(AuthFailedException::class);

        $this->repository->method('matching')->willReturn([
            (object)[
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'email' => 'admin@admin.com',
                'password' => password_hash('132456', PASSWORD_BCRYPT),
            ]
        ]);

        $authUser = new AuthUser($this->repository, $this->criteria, $this->clientToken);
        $authUser->execute('admin@admin.com', '13245');
    }
}