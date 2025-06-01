<?php

namespace App\Application\UseCase\Auth;

use App\Infrastructure\Auth\Interfaces\JWTInterface;

class ClientToken
{
    public function __construct(protected JWTInterface $jwt)
    {
    }

    public function execute(string $userId): string
    {
        $this->jwt->setData((object)[
            'user_id' => $userId,
        ]);
        return $this->jwt->createToken();
    }
}
