<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\UseCase\Clients\AddFavoriteProduct;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Web\InputInterface;
use App\Web\OutputInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class ClientFavoriteProductController
{
    public function __construct(
        protected InputInterface $input,
        protected OutputInterface $output,
        protected LoggerInterface $logger,
        protected AddFavoriteProduct $addFavoriteProduct
    ) {
    }

    public function addFavorite(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var object{id: string} $urlParams */
            $urlParams = (object)$this->input->getUrlParameters($request);

            /** @var object{product_id: int} $data */
            $data = (object)$this->input->getData($request);

            $product = $this->addFavoriteProduct->execute($urlParams->id, $data->product_id);

            return $this->output->getResponse($response, 201, $product);
        } catch (AlreadyExistsException $e) {
            return $this->output->getResponseError($response, 409, $e->getMessage());
        } catch (Exception $e) {
            $this->logger->error('Error adding favorite product', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, $e->getMessage());
        }
    }
}
