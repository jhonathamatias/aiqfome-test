<?php

namespace HyperfTest\Unit;

use App\Infrastructure\Auth\BearerToken;
use App\Infrastructure\Auth\Exceptions\InvalidBearerTokenException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BearerToken::class)]
class BearerTokenTest extends TestCase
{
    public static function negativeBearerTokenDataProvider(): array
    {
        return [
            ['', InvalidBearerTokenException::class, 'Invalid token'],
            ['Bearer', InvalidBearerTokenException::class, 'Invalid token'],
            ['token123', InvalidBearerTokenException::class, 'Invalid token'],
        ];
    }

    /**
     * @dataProvider negativeBearerTokenDataProvider
     */
    public function testBearerTokenComponentShouldNotAcceptWrongBearerToken(
        string $token,
        string $expectException,
        string $expectExceptionMessage
    ) {
        $this->expectException($expectException);
        $this->expectExceptionMessage($expectExceptionMessage);

        new BearerToken($token);
    }

    public function testBearerTokenComponentShouldReturnBearerTokenValid()
    {
        $bearerToken = new BearerToken('Bearer token123');
        $this->assertEquals('token123', (string)$bearerToken);
    }
}
