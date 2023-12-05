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

    public function createCreateEntityRequest(array $data, string $token): Request
    {
        return new Request(
            'POST',
            $this->urlFactory->createEntity(),
            [
                'Authorization' => sprintf('Bearer %s', $token),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            json_encode($data)
        );
    }

    public function createSearchRequest(string $term, int $page = 0, int $limit = 10): Request
    {
        return new Request(self::GET, $this->urlFactory->search($term, $page, $limit));
    }

    public function createValidateTokenRequest(string $token): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->validateToken(),
            [
                'Authorization' => sprintf('Bearer %s', $token),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );
    }
}
