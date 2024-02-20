<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\Client\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Throwable;

class Client
{
    private int $lastQueryCount = -1;

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactory $requestFactory,
        private readonly MapperService $mapperFactory,
    ) {
    }

    public function getEntity(string $entityUuid): Entity
    {
        $request = $this->requestFactory->createGetEntityRequest($entityUuid);

        /** @var Entity */
        return $this->processRequestAndMapResponse($request, Entity::class);
    }

    public function getEntities(FilterCriteria $criteria): EntityCollection
    {
        $request = $this->requestFactory->createGetEntitiesRequest($criteria);

        /** @var EntityCollection */
        return $this->processRequestAndMapResponse($request, EntityCollection::class);
    }

    public function getMyEntities(FilterCriteria $criteria, string $token): EntityCollection
    {
        $request = $this->requestFactory->createGetMyEntitiesRequest($criteria, $token);

        /** @var EntityCollection */
        return $this->processRequestAndMapResponse($request, EntityCollection::class);
    }

    public function getDefinition(string $definitionUuid): Definition
    {
        $request = $this->requestFactory->createGetDefinitionRequest($definitionUuid);

        /** @var Definition */
        return $this->processRequestAndMapResponse($request, Definition::class);
    }

    public function createEntity(array $data, string $definitionUuid, string $token): Entity
    {
        $payload = [
            'data' => $data,
            'definition' => '/api/definitions/' . $definitionUuid,
        ];

        $request = $this->requestFactory->createCreateEntityRequest($payload, $token);

        /** @var Entity */
        return $this->processRequestAndMapResponse($request, Entity::class);
    }

    public function validateToken(string $token): bool
    {
        $request = $this->requestFactory->createValidateTokenRequest($token);

        $response = $this->httpClient->sendRequest($request);

        return $response->getStatusCode() === 200;
    }

    public function getOAuthToken(string $clientId, string $clientSecret): string|null
    {
        $request = $this->requestFactory->createGetOAuthTokenRequest($clientId, $clientSecret);

        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 201) {
            return null;
        }

        $body = json_decode($response->getBody()->getContents(), true);

        return $body['accessToken'] ?? $body['token'];
    }

    public function getLastQueryCount(): int
    {
        return $this->lastQueryCount;
    }

    private function processRequestAndMapResponse(
        RequestInterface $request,
        string $responseClassMapper
    ): object {
        try {
            $response = $this->httpClient->sendRequest($request);
            $body = $this->extractFromJsonLD($response->getBody()->getContents());
            return $this->mapperFactory
                ->getMapperFor($responseClassMapper)
                ->map($body);
        } catch (Throwable $e) {
            throw new RuntimeException('Error while sending request', 0, $e);
        }
    }

    private function extractFromJsonLD(string $json): string
    {
        $data = json_decode($json, true);
        $this->lastQueryCount = $data['hydra:totalItems'] ?? -1;
        return isset($data['hydra:member']) ? json_encode($data['hydra:member']) : $json;
    }
}
