<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client;

readonly class UrlFactory
{
    public function __construct(
        private string $apiUrl,
    ) {
    }

    public function getEntityByDefinition(string $definitionUuid): string
    {
        return sprintf(
            '%s/entities-by-definition/%s',
            $this->apiUrl,
            $definitionUuid
        );
    }

    public function getEntity(string $entityUuid): string
    {
        return sprintf(
            '%s/entities/%s',
            $this->apiUrl,
            $entityUuid
        );
    }

    public function getDefinition(string $definitionUuid): string
    {
        return sprintf(
            '%s/definitions/%s',
            $this->apiUrl,
            $definitionUuid
        );
    }

    public function createEntity(): string
    {
        return sprintf('%s/entities', $this->apiUrl);
    }

    public function search(string $term, int $page = 0, int $limit = 10): string
    {
        return sprintf(
            '%s/search/term=%s?page=%d&limit=%d',
            $this->apiUrl,
            $term,
            $page,
            $limit
        );
    }

    public function getEntities(): string
    {
        return sprintf('%s/entities', $this->apiUrl);
    }

    public function validateToken(): string
    {
        return sprintf('%s/token/validate', $this->apiUrl);
    }
}
