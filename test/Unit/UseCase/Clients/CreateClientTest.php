<?php

namespace HyperfTest\Unit\UseCase\Clients;

use App\Application\UseCase\Clients\CreateClient;
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Exceptions\AlreadyExistsException;

#[CoversClass(CreateClient::class)]
class CreateClientTest extends TestCase
{
    public function testNotCreateClientWithExistingEmail(): void
    {
        $this->expectException(AlreadyExistsException::class);

        $repository = $this->createMock(GenericRepositoryInterface::class);
        $criteria = $this->createMock(CriteriaInterface::class);

        $criteria->method('equal');

        $repository->method('setCollectionName');
        
        $repository->method('matching')->willReturn([(object)['id' => '1223rew3q5-1234-5678-90ab-cdef12345678']]);
        $repository->method('getById')->willReturn(new \stdClass());
        $repository->method('save');
        $repository->method('getInsertedLastId')->willReturn('1223rew3q5-1234-5678-90ab-cdef12345678');

        $createClient = new CreateClient($repository, $criteria);

        $createClient->execute('Jhonatha Matias', 'jhonatha@gmail.com');
    }

    public function testCreateClientSuccessfully(): void
    {
        $repository = $this->createMock(GenericRepositoryInterface::class);
        $criteria = $this->createMock(CriteriaInterface::class);

        $criteria
            ->method('equal');

        $repository->method('setCollectionName');
        $repository->method('matching')->willReturn([]);

        $expectedClient = new \stdClass();
        $expectedClient->id = '550e8400-e29b-41d4-a716-446655440000';
        $expectedClient->name = 'John Doe';
        $expectedClient->email = 'john@example.com';

        $repository->method('getById')->willReturn($expectedClient);
        $repository->method('save');
        $repository->method('getInsertedLastId')->willReturn('550e8400-e29b-41d4-a716-446655440000');

        $createClient = new CreateClient($repository, $criteria);

        $result = $createClient->execute('John Doe', 'john@example.com');

        $this->assertEquals($expectedClient, $result);
    }
}