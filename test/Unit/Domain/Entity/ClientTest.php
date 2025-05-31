<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\Entity;

use App\Domain\Entity\Client;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Domain\ValueObjects\Email;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Client::class)]
class ClientTest extends TestCase
{
    public function testSaveThrowsExceptionWhenClientAlreadyExists(): void
    {
        $this->expectException(AlreadyExistsException::class);

        $client = new Client();
        $client->id = '123';
        $client->name = 'Jhonatha Matias';
        $client->email = new Email('jhonathamatias@teste.com');

        $client->canBeSave();
    }

    public function testSaveReturnsTrueWhenClientDoesNotExist(): void
    {
        $client = new Client();
        $client->name = 'Jhonatha Matias';
        $client->email = new Email('jhonathamatias@teste.com');

        $this->assertTrue($client->canBeSave(), 'Client should be able to be saved when it does not exist');
    }
}
