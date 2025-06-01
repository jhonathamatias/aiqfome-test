<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\UseCase\Clients\AddFavoriteProduct;
use App\Application\UseCase\Clients\CreateClient;
use App\Application\UseCase\Clients\DeleteClient;
use App\Application\UseCase\Clients\GetClient;
use App\Application\UseCase\Clients\UpdateClient;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Exception\NotFoundException;
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
        protected CreateClient $createClient,
        protected GetClient $getClient,
        protected UpdateClient $updateClient,
        protected DeleteClient $deleteClient,
        protected AddFavoriteProduct $addFavoriteProduct
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
            return $this->output->getResponseError($response, 400, $e->getMessage());
        }
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var array<string, string> $urlParams */
            $urlParams = $this->input->getUrlParameters($request);

            $validator = $this->validationFactory->make(
                $urlParams,
                ['id' => 'uuid|required']
            );
            $validator->validate();

            $client = $this->getClient->execute($urlParams['id']);

            return $this->output->getResponse($response, 200, $client);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return $this->output->getResponseError($response, 422, 'Validation failed', $errors);
        } catch (Exception) {
            return $this->output->getResponseError($response, 400, 'An error occurred while retrieving the client');
        }
    }


    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var array<string, string> $urlParams */
            $urlParams = $this->input->getUrlParameters($request);
            $body = $this->input->getData($request);

            /** @var array{id: string, name?: string, email?: string} $data */
            $data = array_merge($urlParams, $body);
            
            $validator = $this->validationFactory->make(
                $data,
                [
                    'id' => 'uuid|required',
                    'name' => 'string|nullable',
                    'email' => 'email|nullable'
                ]
            );
            $validator->validate();

            $this->updateClient->execute(
                $data['id'],
                $data['name'] ?? null,
                $data['email'] ?? null
            );

            return $this->output->getResponse($response, 200, ['message' => 'Client updated successfully']);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
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
            return $this->output->getResponseError($response, 400, 'An error occurred while updating the client');
        }
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var array<string, string> $urlParams */
            $urlParams = $this->input->getUrlParameters($request);

            $validator = $this->validationFactory->make(
                $urlParams,
                ['id' => 'uuid|required']
            );
            $validator->validate();

            $this->deleteClient->execute($urlParams['id']);

            return $this->output->getResponse($response, 200, ['message' => 'Client deleted successfully']);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return $this->output->getResponseError($response, 422, 'Validation failed', $errors);
        } catch (Exception) {
            return $this->output->getResponseError($response, 400, 'An error occurred while deleting the client');
        }
    }
    
    public function addFavoriteProduct(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var array<string, string> $urlParams */
            $urlParams = $this->input->getUrlParameters($request);
            $body = $this->input->getData($request);

            /** @var array{id: string, product_id: int} $data */
            $data = array_merge($urlParams, $body);
            
            $validator = $this->validationFactory->make(
                $data,
                [
                    'id' => 'uuid|required',
                    'product_id' => 'integer|required'
                ]
            );
            $validator->validate();

            $product = $this->addFavoriteProduct->execute($data['id'], $data['product_id']);

            return $this->output->getResponse($response, 201, $product);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return $this->output->getResponseError($response, 422, 'Validation failed', $errors);
        } catch (Exception $e) {
            return $this->output->getResponseError($response, 400, $e->getMessage());
        }
    }
}
