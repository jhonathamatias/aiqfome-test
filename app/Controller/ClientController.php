<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\UseCase\Clients\CreateClient;
use App\Application\UseCase\Clients\DeleteClient;
use App\Application\UseCase\Clients\GetAllClients;
use App\Application\UseCase\Clients\GetClient;
use App\Application\UseCase\Clients\UpdateClient;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Exception\NotFoundException;
use App\Web\InputInterface;
use App\Web\OutputInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class ClientController
{
    public function __construct(
        protected InputInterface $input,
        protected OutputInterface $output,
        protected LoggerInterface $logger,
        protected CreateClient $createClient,
        protected GetClient $getClient,
        protected UpdateClient $updateClient,
        protected DeleteClient $deleteClient,
        protected GetAllClients $getAllClients
    ) {
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var object{name: string, email: string} $data */
            $data = (object)$this->input->getData($request);

            $user = $this->createClient->execute(
                name: $data->name,
                email: $data->email
            );

            return $this->output->getResponse($response, 201, $user);
        } catch (AlreadyExistsException $e) {
            return $this->output->getResponseError($response, 409, $e->getMessage());
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
            /** @var object{id: string} $urlParams */
            $urlParams = (object)$this->input->getUrlParameters($request);

            $client = $this->getClient->execute($urlParams->id);

            return $this->output->getResponse($response, 200, $client);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (Exception $e) {
            $this->logger->error('Error getting client', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, 'An error occurred while retrieving the client');
        }
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var object{id: string} $urlParams */
            $urlParams = (object)$this->input->getUrlParameters($request);

            /** @var object{name?: string, email?: string} $data */
            $data = $this->input->getData($request);

            $this->updateClient->execute(
                $urlParams->id,
                $data->name ?? null,
                $data->email ?? null
            );

            return $this->output->getResponse($response, 200, ['message' => 'Client updated successfully']);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (AlreadyExistsException $e) {
            return $this->output->getResponseError($response, 409, $e->getMessage());
        } catch (Exception $e) {
            $this->logger->error('Error updating client', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, 'An error occurred while updating the client');
        }
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var object{id: string} $urlParams */
            $urlParams = (object)$this->input->getUrlParameters($request);

            $this->deleteClient->execute($urlParams->id);

            return $this->output->getResponse($response, 200, ['message' => 'Client deleted successfully']);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (Exception $e) {
            $this->logger->error('Error deleting client', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, 'An error occurred while deleting the client');
        }
    }
    
    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $urlParams = (object)$this->input->getUrlParameters($request);

            $clients = $this->getAllClients->execute(isset($urlParams->limit) ? (int)$urlParams->limit : 100);

            return $this->output->getResponse($response, 200, $clients);
        } catch (Exception $e) {
            $this->logger->error('Error listing clients', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, 'An error occurred while retrieving the clients');
        }
    }
}
