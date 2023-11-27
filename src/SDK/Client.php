<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\Client\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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

    public function getEntitiesByDefinition(string $definitionUuid): EntityCollection
    {
        $request = $this->requestFactory->createGetEntityByDefinitionRequest($definitionUuid);
        $response = $this->httpRequest($request);

        /** @var EntityCollection */
        return $this->mapperFactory
            ->getMapperFor(EntityCollection::class)
            ->map($response->getBody()->getContents());
    }

    public function getEntity(string $entityUuid): Entity
    {
        $request = $this->requestFactory->createGetEntityRequest($entityUuid);
        $response = $this->httpRequest($request);

        /** @var Entity */
        return $this->mapperFactory
            ->getMapperFor(Entity::class)
            ->map($response->getBody()->getContents());
    }

    public function getDefinition(string $definitionUuid): Definition
    {
        $request = $this->requestFactory->createGetDefinitionRequest($definitionUuid);
        $response = $this->httpRequest($request);

        /** @var Definition */
        return $this->mapperFactory
            ->getMapperFor(Definition::class)
            ->map($response->getBody()->getContents());
    }

    private function httpRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->httpClient->sendRequest($request);
        } catch (Throwable $e) {
            throw new RuntimeException('Error while sending request', 0, $e);
        }
    }
}
