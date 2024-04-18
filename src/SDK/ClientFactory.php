<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use GuzzleHttp\Client as GuzzleClient;
use JsonHub\SDK\Client\Mapper\DefinitionMapper;
use JsonHub\SDK\Client\Mapper\EntityCollectionMapper;
use JsonHub\SDK\Client\Mapper\EntityMapper;
use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\Client\RequestFactory;
use JsonHub\SDK\Client\UrlFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class ClientFactory
{
    public static function create(
        string $apiUrl,
        LoggerInterface|null $logger = null,
        ClientInterface|null $httpClient = null,
    ): Client {
        return new Client(
            $httpClient ?? new GuzzleClient(),
            new RequestFactory(
                new UrlFactory($apiUrl)
            ),
            new MapperService([
                new EntityMapper(),
                new EntityCollectionMapper(
                    new EntityMapper(),
                ),
                new DefinitionMapper(),
            ]),
            $logger,
        );
    }
}
