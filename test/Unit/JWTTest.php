<?php

namespace HyperfTest\Unit;

use App\Infrastructure\Auth\BearerToken;
use App\Infrastructure\Auth\JWT;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

#[CoversClass(JWT::class)]
class JWTTest extends TestCase
{
    public function getConfig(): Configuration
    {
        $privateKey = <<<KEY
-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC0XScGRzi44UWb
CrWBhAMSSPuD60/vJrHO6JM+CnEynBoyTRtvucGAk5WvIeYITuPCAWhxNuk2/k7R
QOPs3dM6ZxVAMQTG5CC+VqyR3PA4M1pmi1c0M4wpXcnFGV0RTkMT/06MuIlK2Rlc
5i73elgfJ/W9u/qTozB+Zkk7h7GNkaxIo3bttlF+xmUQ4ttQtFw/zUla3v8A+GeN
8Sit0zlknapyRFsoOJkwZpqwTPgAt3cyvLtF4f17ZdMIYk1PWfEQA21LF30pAXBA
L3qmyREf0t4rhHQDQfWf1LMrVimepc0o0oeV3fbAApyaL6yfIFoILdV0WBX4ttxZ
dM8btymNAgMBAAECggEAF5EsbjeULnl7p4c+BuRW+maq5qaH//m7C2F8YS5AHZrO
xCUrIULRCUSU2cNrfp5q8t34OwLadPhvGAyLO6y90vMb5b1XSx234ZG1uYF4VKpG
dQEJWKV7pP6/meTMh7GwNhwKtCcmBAUGDrYiYO04N9Si2YiHH2TCtgwWJD8rcLyQ
g4zDB/fLHWYQ3Udj9uEZidVffHHj5D/j41J4+nCSqcuj8k3ReFVv5nRDw/7pxo4r
Vav4nIzI4J2e2aI2rfWYEgtHCCn1bxMczNS/RS1unlWT2PbYBotkVTxf0CViD0zL
cjmjfso0hn2TR84y6CxU9fvYoEjfalG3l94p+2RhiQKBgQDKhkifpbxBCAsWfNgZ
SelEsZRh/uooEJlGmjeZZTeeZHt8RQbzD+qi7dAWgyVPwJPV+dh5nLFdqeB1VOzU
thQnH0Pf4cElD8dIsiU3UIISHRW2yiKPk5oLMDGQeV6I5GWQkOz8OI0oMhXvbxq8
ZUyzZrKHtPJls5+l8nN0rwCaNwKBgQDj/Okkqs38nihbDdid7OR4YxtEsk+/+bmq
kmVtLRkXWjAUShXkbumv/Yu1LSJ2XKQAc8P2JpZS7T+gkXyEVRBvfUD8Lcbmht2G
H8LpLR72hwxAE8tfLtdc1cbl+Rnw57m1DyoxAmJitnBmdJlU4YAH5jxYw9YunCaP
qThDwfZoWwKBgQCISEa0j62XwVbcwhQVKGR9olXsf8KIRWsvVHWXlsIPpyRlonmj
tJE7JHbDv2qrOTcCZYdjhqrgEYGG0ygvl3sGPIbLMDptuylqZN6gU1/D2qiTAYCy
RywA3WRtCQ8xZShnWO7wZwmuA844+fXu0ugDTVdT9NKs12vWGsnQujbYjwKBgHgi
vx/pOV4SYSWJ6ElfmQyu/KF4bHm2t9VgCz46c2xQw+ENIPgcUdvA+SthOzWvn2P1
nJ9Kug+8oLiVsU1yHZUCJYo8/QBgtL6GYgjJE/XIN/ZT9+iSID31EDgCyV6eXMdB
1HO60+k8RYJXkqydnv+KnOYRW/13nwI4o0NhI9zdAoGAPFSOYKoK1OyJcZIpfkct
u9n6vhvjmUOtFme6gREP1TOmptcaahPnav56QaWqcxEE5CBe4yCcp4GzJ3QT3etw
FM5QXZ6mGDl7fr/3PMrF8Z28Tjzoy5e4aVzA75T1dOrTpaGdgmwHggH9RQwgWyzv
wfFTSgiT/i68CXZKUVqA4M0=
-----END PRIVATE KEY-----
KEY;
        $publicKey = <<<KEY
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtF0nBkc4uOFFmwq1gYQD
Ekj7g+tP7yaxzuiTPgpxMpwaMk0bb7nBgJOVryHmCE7jwgFocTbpNv5O0UDj7N3T
OmcVQDEExuQgvlaskdzwODNaZotXNDOMKV3JxRldEU5DE/9OjLiJStkZXOYu93pY
Hyf1vbv6k6MwfmZJO4exjZGsSKN27bZRfsZlEOLbULRcP81JWt7/APhnjfEordM5
ZJ2qckRbKDiZMGaasEz4ALd3Mry7ReH9e2XTCGJNT1nxEANtSxd9KQFwQC96pskR
H9LeK4R0A0H1n9SzK1YpnqXNKNKHld32wAKcmi+snyBaCC3VdFgV+LbcWXTPG7cp
jQIDAQAB
-----END PUBLIC KEY-----
KEY;

        $mockKey = $this->createMock(Key::class);
        $mockKey->method('contents')
            ->willReturn($privateKey);

        $mockPublicKey = $this->createMock(Key::class);
        $mockPublicKey->method('contents')
            ->willReturn($publicKey);

        return Configuration::forAsymmetricSigner(
            new Sha256(),
            $mockKey,
            $mockPublicKey
        );
    }

    public function testJwtComponentShouldNotAcceptInvalidToken()
    {
        $this->expectExceptionMessage('Invalid token');

        $clock = $this->createMock(ClockInterface::class);
        $clock->method('now')
            ->willReturn(new \DateTimeImmutable());

        $jwt = new JWT($this->getConfig(), $clock);
        $jwt->setToken(new BearerToken('Bearer token123'));
        $jwt->validate();
    }

    public function testJwtComponentShouldReturnValidToken()
    {
        $fixedTime = new \DateTimeImmutable('2025-06-01');

        $clock = $this->createMock(ClockInterface::class);
        $clock->method('now')->willReturn($fixedTime);

        $jwt = new JWT($this->getConfig(), $clock);

        // ✅ Gera o token com o mesmo clock fixo
        $token = $jwt->createToken();

        $jwt->setToken(new BearerToken('Bearer ' . $token));

        $this->assertTrue($jwt->validate(), 'Expected token to be valid, but validation failed.');
    }
}
