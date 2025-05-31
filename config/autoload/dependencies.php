<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Infrastructure\GenericRepository\CriteriaInterface;
use App\Infrastructure\GenericRepository\GenericRepositoryInterface;
use App\Infrastructure\GenericRepository\HyperfORM\Criteria;
use App\Infrastructure\GenericRepository\HyperfORM\Repository;
use App\Web\HyperfInOut\Input;
use App\Web\HyperfInOut\JsonOutput;
use App\Web\InputInterface;
use App\Web\OutputInterface;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;

return [
    ResponseInterface::class => Response::class,

    OutputInterface::class => JsonOutput::class,
    InputInterface::class => Input::class,

    GenericRepositoryInterface::class => Repository::class,
    CriteriaInterface::class => Criteria::class,
];
