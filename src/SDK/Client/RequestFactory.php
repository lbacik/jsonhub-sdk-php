<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client;

use GuzzleHttp\Psr7\Request;
use JsonHub\SDK\FilterCriteria;

readonly class RequestFactory
{
    private const GET = 'GET';
    private const POST = 'POST';

    public function __construct(
        private UrlFactory $urlFactory,
    ) {
    }

    public function createGetEntityByDefinitionRequest(string $definitionUuid): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getEntityByDefinition($definitionUuid),
            $this->generateHeaders()
        );
    }

    public function createGetEntitiesRequest(FilterCriteria $criteria): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getEntities($criteria->generateQueryString()),
            $this->generateHeaders()
        );
    }

    public function createGetMyEntitiesRequest(FilterCriteria $criteria, string|null $token = null): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getMyEntities($criteria->generateQueryString()),
            $this->generateHeaders($token)
        );
    }

    public function createGetEntityRequest(string $entityUuid): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getEntity($entityUuid),
            $this->generateHeaders()
        );
    }

    public function createGetDefinitionRequest(string $definitionUuid): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getDefinition($definitionUuid),
            $this->generateHeaders()
        );
    }

    public function createCreateEntityRequest(array $data, string $token): Request
    {
        return new Request(
            'POST',
            $this->urlFactory->createEntity(),
            $this->generateHeaders($token),
            json_encode($data)
        );
    }

    public function createValidateTokenRequest(string $token): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->validateToken(),
            $this->generateHeaders($token)
        );
    }

    private function generateHeaders(?string $token = null): array
    {
        $headers =  [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($token) {
            $headers['Authorization'] = sprintf('Bearer %s', $token);
        }

        return $headers;
    }
}
