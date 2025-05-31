<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\UseCase\Clients\CreateClient;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Web\InputInterface;
use App\Web\OutputInterface;
use Exception;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class ClientController
{
    public function __construct(
        protected InputInterface $input,
        protected OutputInterface $output,
        protected ValidatorFactoryInterface $validationFactory,
        protected LoggerInterface $logger,
        protected CreateClient $createClient
    ) {
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var array{name: string, email: string} $body */
            $body = $this->input->getData($request);

            $validator = $this->validationFactory->make(
                $body,
                ['name' => 'required|string', 'email' => 'required|email']
            );
            $validator->validate();

            $user = $this->createClient->execute(
                name: $body['name'],
                email: $body['email']
            );

            return $this->output->getResponse($response, 201, $user);
        } catch (AlreadyExistsException $e) {
            return $this->output->getResponseError($response, 409, $e->getMessage());
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return $this->output->getResponseError($response, 422, 'Validation failed', $errors);
        } catch (Exception $e) {
            $this->logger->error('Error creating client', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, 'An error occurred while creating the client');
        }
    }
}
