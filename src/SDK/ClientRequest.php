<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use JsonHub\SDK\Client\MapperService;
use JsonHub\SDK\ClientRequest\JsonHubConnector;
use JsonHub\SDK\ClientRequest\RequestCollection;
use JsonHub\SDK\Exception\UnauthorizedException;
use RuntimeException;

class ClientRequest
{
    private int $lastQueryCount = -1;

    public function __construct(
        private readonly JsonHubConnector $connector,
        private readonly RequestCollection $requestCollection,
        private readonly MapperService $mapperFactory,
    ) {
    }

    public function request(
        string $requestClass,
        array|null $payload = null,
        string|null $responseClassMapper = null
    ): object|null {
        $request = $this->requestCollection->getRequest($requestClass, $payload);
        $response = $this->connector->send($request);

        match ($response->status()) {
            200, 201, 204 => null,
            401 => throw UnauthorizedException::create(),
            default => throw new RuntimeException('Response status code', $response->status()),
        };

        $body = $this->extractFromJsonLD($response->body());

        if ($responseClassMapper === null) {
            return null;
        }

        return $this->mapperFactory
            ->getMapperFor($responseClassMapper)
            ->map($body);
    }

    public function getLastQueryCount(): int
    {
        return $this->lastQueryCount;
    }

    private function extractFromJsonLD(string $json): string
    {
        $data = json_decode($json, true);
        $this->lastQueryCount = $data['hydra:totalItems'] ?? -1;
        return isset($data['hydra:member']) ? json_encode($data['hydra:member']) : $json;
    }
}
