<?php

namespace App\Middleware;

use App\Infrastructure\Auth\BearerToken;
use App\Infrastructure\Auth\Interfaces\JWTInterface;
use App\Web\OutputInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected JWTInterface $jwt,
        protected OutputInterface $output
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response      = new Response();
            $authorization = $request->getHeader('Authorization')[0] ?? null;

            if ($authorization === null) {
                return $this->output->getResponseError($response, 401, 'No authorization provided');
            }

            $this->jwt->setToken(new BearerToken($authorization));
            $this->jwt->validate();

            $data = $this->jwt->getData();

            if ($data === null) {
                throw new \Exception('Invalid token');
            }

            $requestBody = $request->getParsedBody();

            if (false === isset($data->user_id)) {
                throw new \Exception('Invalid token');
            }

            $request = $request->withParsedBody($requestBody);

            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->output->getResponseError(new Response(), 401, $e->getMessage());
        }
    }
}
