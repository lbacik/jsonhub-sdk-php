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

    public function createGetEntitiesRequest(FilterCriteria $criteria, string|null $token = null): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getEntities($criteria->generateQueryString()),
            $this->generateHeaders($token, 'application/ld+json'),
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

    public function createGetEntityRequest(string $entityUuid, string|null $token = null): Request
    {
        return new Request(
            self::GET,
            $this->urlFactory->getEntity($entityUuid),
            $this->generateHeaders($token)
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

    public function createUpdateEntityRequest(string $id, array $data, string $token): Request
    {
        return new Request(
            'PATCH',
            $this->urlFactory->getEntity($id),
            $this->generateHeaders($token, content: 'application/merge-patch+json'),
            json_encode($data)
        );
    }

    public function createDeleteEntityRequest(string $id, string $token): Request
    {
        return new Request(
            'DELETE',
            $this->urlFactory->getEntity($id),
            $this->generateHeaders($token),
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

    public function createGetOAuthTokenRequest(string $clientId, string $clientSecret): Request
    {
        return new Request(
            self::POST,
            $this->urlFactory->getOAuthToken(),
            $this->generateHeaders(),
            json_encode([
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'grantType' => 'client_credentials',
            ])
        );
    }

    private function generateHeaders(
        ?string $token = null,
        string $accept = 'application/json',
        string $content = 'application/json',
    ): array {
        $headers =  [
            'Content-Type' => $content,
            'Accept' => $accept,
        ];

        if ($token) {
            $headers['Authorization'] = sprintf('Bearer %s', $token);
        }

        return $headers;
    }
}
