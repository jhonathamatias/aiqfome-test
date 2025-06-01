<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Web\OutputInterface;
use Hyperf\HttpServer\Response;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Validation\ValidatorFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BodyValidationMiddleware implements MiddlewareInterface
{
    public function __construct(protected ValidatorFactory $validationFactory, protected OutputInterface $output)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var Dispatched $dispatched
         */
        $dispatched = $request->getAttribute(Dispatched::class);

        if (null === $dispatched->handler) {
            return $handler->handle($request);
        }

        $options = (object)$dispatched->handler->options;

        if (false === isset($options->body_rules)) {
            return $handler->handle($request);
        }

        /** @var string[] $data */
        $data = $request->getParsedBody();

        $validator = $this->validationFactory->make(
            $data,
            $options->body_rules
        );

        if ($validator->fails()) {
            return $this->output->getResponseError(new Response(), 422, 'Validation error', $validator->errors());

        }
        return $handler->handle($request);
    }
}
