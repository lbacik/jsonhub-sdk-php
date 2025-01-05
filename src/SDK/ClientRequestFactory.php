<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use JsonHub\SDK\Client\Mapper\DefinitionCollectionMapper;
use JsonHub\SDK\Client\Mapper\DefinitionMapper;
use JsonHub\SDK\Client\Mapper\EntityCollectionMapper;
use JsonHub\SDK\Client\Mapper\EntityMapper;
use JsonHub\SDK\Client\Mapper\ParentEntityMapper;
use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\ClientRequest\JsonHubConnector;
use JsonHub\SDK\ClientRequest\RequestAdapter;
use JsonHub\SDK\ClientRequest\RequestCollection;
use JsonHub\SDK\Request\ActivationRequest;
use JsonHub\SDK\Request\ForgotPasswordResetRequest;
use JsonHub\SDK\Request\RegisterRequest;
use JsonHub\SDK\Request\ResendActivationRequest;
use JsonHub\SDK\Request\SendForgotPasswordRequest;

class ClientRequestFactory
{
    public static function create(
        string $apiUrl,
    ): ClientRequest {
        return new ClientRequest(
            new JsonHubConnector($apiUrl),
            self::requests(),
            self::createMappers()
        );
    }

    public static function createMappers(): MapperService
    {
        $definitionMapper = new DefinitionMapper(
            new ParentEntityMapper(),
        );

        $entityMapper = new EntityMapper($definitionMapper);

        $mappers = new MapperService(
            [
                $entityMapper,
                new EntityCollectionMapper($entityMapper),
                $definitionMapper,
                new DefinitionCollectionMapper($definitionMapper),
            ]
        );

        return $mappers;
    }

    public static function requests(): RequestCollection
    {
        return new RequestCollection(
            [
                new RequestAdapter(RegisterRequest::class),
                new RequestAdapter(ActivationRequest::class),
                new RequestAdapter(ResendActivationRequest::class),
                new RequestAdapter(SendForgotPasswordRequest::class),
                new RequestAdapter(ForgotPasswordResetRequest::class),
            ]
        );
    }
}
