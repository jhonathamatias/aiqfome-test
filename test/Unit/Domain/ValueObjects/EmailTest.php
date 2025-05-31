<?php

declare(strict_types=1);

namespace HyperfTest\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Email::class)]
class EmailTest extends TestCase
{
    #[DataProvider('invalidEmailProvider')]
    public function testShouldThrowExceptionWhenEmailIsInvalid(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email');

        new Email($value);
    }

    #[DataProvider('validEmailProvider')]
    public function testShouldReturnEmailStringIfValid(string $value): void
    {
        $email = new Email($value);

        $this->assertEquals($value, (string) $email);
    }

    public static function invalidEmailProvider(): array
    {
        return [
            'empty string' => [''],
            'no @ symbol' => ['invalidemail.com'],
            'no domain' => ['invalid@'],
            'spaces' => ['invalid email@domain.com'],
        ];
    }

    public static function validEmailProvider(): array
    {
        return [
            'simple' => ['user@example.com'],
            'dot in local part' => ['first.last@example.com'],
            'plus sign' => ['user+alias@example.com'],
            'numeric domain' => ['user@123.123.123.123'],
            'subdomain' => ['user@mail.server.com'],
        ];
    }
}
