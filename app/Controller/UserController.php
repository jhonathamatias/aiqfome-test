<?php

namespace App\Controller;

use App\Application\UseCase\Auth\AuthUser;
use App\Exception\AuthFailedException;
use App\Web\InputInterface;
use App\Web\OutputInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController
{
    public function __construct(
        protected InputInterface $input,
        protected OutputInterface $output,
        protected AuthUser $authUser
    ) {
    }

    public function auth(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = (object)$this->input->getData($request);
            
            $token = $this->authUser->execute($data->email, $data->password);
            
            return $this->output->getResponse($response, 200, (object)[
                'access_token' => $token,
            ]);
        } catch (AuthFailedException $e) {
            return $this->output->getResponseError($response, 401, $e->getMessage());
        } catch (\Exception $e) {
            return $this->output->getResponseError($response, 400, $e->getMessage());
        }
    }
}
