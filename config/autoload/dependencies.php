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
use App\Infrastructure\GenericRepository;
use App\Infrastructure\HttpClient\HttpClientInterface;
use App\Web\HyperfInOut\Input;
use App\Web\HyperfInOut\JsonOutput;
use App\Web\InputInterface;
use App\Web\OutputInterface;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;
use App\Application\DependencyInjection;

return [
    ResponseInterface::class          => Response::class,

    OutputInterface::class            => JsonOutput::class,
    InputInterface::class             => Input::class,

    GenericRepositoryInterface::class => GenericRepository\HyperfORM\Repository::class,
    CriteriaInterface::class          => Criteria::class,

    HttpClientInterface::class        => DependencyInjection\HttpClientFactoryInvoker::class,
];
