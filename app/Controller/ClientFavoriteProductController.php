<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\UseCase\Clients\AddFavoriteProduct;
use App\Application\UseCase\Clients\AddManyFavoritesProducts;
use App\Application\UseCase\Clients\GetFavoritesProducts;
use App\Domain\Entity\Exceptions\AlreadyExistsException;
use App\Exception\NotFoundException;
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
        protected AddFavoriteProduct $addFavoriteProduct,
        protected AddManyFavoritesProducts $addManyFavoritesProducts,
        protected GetFavoritesProducts $getFavoritesProducts
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
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
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

    public function AddManyFavorites(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var object{id: string} $urlParams */
            $urlParams = (object)$this->input->getUrlParameters($request);

            /** @var object{product_ids: array<int>} $data */
            $data = (object)$this->input->getData($request);

            $products = $this->addManyFavoritesProducts->execute($urlParams->id, $data->product_ids);

            return $this->output->getResponse($response, 207, $products);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (Exception $e) {
            $this->logger->error('Error adding many favorite products', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, $e->getMessage());
        }
    }

    public function listFavorites(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            /** @var object{id: string, limit?: int} $urlParams */
            $urlParams = (object)$this->input->getUrlParameters($request);

            $favorites = $this->getFavoritesProducts->execute(
                $urlParams->id,
                isset($urlParams->limit) ? (int)$urlParams->limit : 100
            );

            return $this->output->getResponse($response, 200, $favorites);
        } catch (NotFoundException $e) {
            return $this->output->getResponseError($response, 404, $e->getMessage());
        } catch (Exception $e) {
            $this->logger->error('Error listing favorite products', [
                'exception' => $e,
                'request' => $this->input->getData($request),
            ]);
            return $this->output->getResponseError($response, 400, $e->getMessage());
        }
    }
}
