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
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactory $requestFactory,
        private readonly MapperService $mapperFactory,
    ) {
    }

//    public function getEntitiesByDefinition(string $definitionUuid): EntityCollection
//    {
//        $request = $this->requestFactory->createGetEntityByDefinitionRequest($definitionUuid);
//
//        /** @var EntityCollection */
//        return $this->processRequestAndMapResponse($request, EntityCollection::class);
//    }

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

    private function processRequestAndMapResponse(
        RequestInterface $request,
        string $responseClassMapper
    ): object {
        try {
            $response = $this->httpClient->sendRequest($request);
            return $this->mapperFactory
                ->getMapperFor($responseClassMapper)
                ->map($response->getBody()->getContents());
        } catch (Throwable $e) {
            throw new RuntimeException('Error while sending request', 0, $e);
        }
    }
}
