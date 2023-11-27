<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client;

use GuzzleHttp\Psr7\Request;

readonly class RequestFactory
{
    private const GET = 'GET';

    public function __construct(
        private UrlFactory $urlFactory,
    ) {
    }

    public function createGetEntityByDefinitionRequest(string $definitionUuid): Request
    {
        return new Request(self::GET, $this->urlFactory->getEntityByDefinition($definitionUuid));
    }

    public function createGetEntityRequest(string $entityUuid): Request
    {
        return new Request(self::GET, $this->urlFactory->getEntity($entityUuid));
    }

    public function createGetDefinitionRequest(string $definitionUuid): Request
    {
        return new Request(self::GET, $this->urlFactory->getDefinition($definitionUuid));
    }
}
