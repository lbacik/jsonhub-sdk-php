<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\Client\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

class Client
{
    private int $lastQueryCount = -1;

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactory $requestFactory,
        private readonly MapperService $mapperFactory,
        private readonly LoggerInterface|null $logger,
    ) {
    }

    public function getEntity(string $entityUuid, string|null $token = null): Entity
    {
        $request = $this->requestFactory->createGetEntityRequest($entityUuid, $token);

        /** @var Entity */
        return $this->processRequestAndMapResponse($request, Entity::class);
    }

    public function getEntities(FilterCriteria $criteria, string|null $token = null): EntityCollection
    {
        $request = $this->requestFactory->createGetEntitiesRequest($criteria, $token);

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

    public function createEntity(Entity $entity, string $token): Entity
    {
        $request = $this->requestFactory->createCreateEntityRequest($entity->toArray(), $token);

        /** @var Entity */
        return $this->processRequestAndMapResponse($request, Entity::class);
    }

    public function updateEntity(Entity $entity, string $token): Entity
    {
        $request = $this->requestFactory->createUpdateEntityRequest($entity->id, $entity->toArray(), $token);

        /** @var Entity */
        return $this->processRequestAndMapResponse($request, Entity::class);
    }

    public function deleteEntity(string $entityUuid, string $token): void
    {
        $request = $this->requestFactory->createDeleteEntityRequest($entityUuid, $token);

        $this->processRequestAndMapResponse($request);
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
        string|null $responseClassMapper = null,
    ): object|null {
        try {
            $response = $this->httpClient->sendRequest($request);

            match ($response->getStatusCode()) {
                200, 201, 204 => null,
                default => throw new RuntimeException('Response status code', $response->getStatusCode()),
            };

            $body = $this->extractFromJsonLD($response->getBody()->getContents());

            if ($responseClassMapper === null) {
                return null;
            }

            return $this->mapperFactory
                ->getMapperFor($responseClassMapper)
                ->map($body);
        } catch (Throwable $exception) {
            $this->log($exception->getMessage() . ' ' . $exception->getCode());
            throw new RuntimeException('Error while sending request', $exception->getCode(), $exception);
        }
    }

    private function extractFromJsonLD(string $json): string
    {
        $data = json_decode($json, true);
        $this->lastQueryCount = $data['hydra:totalItems'] ?? -1;
        return isset($data['hydra:member']) ? json_encode($data['hydra:member']) : $json;
    }

    private function log(string $message, string $level = 'error'): void
    {
        if ($this->logger !== null) {
            $this->logger->{$level}($message);
        }
    }
}
