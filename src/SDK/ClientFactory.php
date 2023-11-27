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

class ClientFactory
{
    public static function create(string $apiUrl): Client
    {
        return new Client(
            new GuzzleClient(),
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
        );
    }
}
