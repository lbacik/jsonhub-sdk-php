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

    public function getEntities(?string $filter = null): string
    {
        $base = sprintf('%s/entities', $this->apiUrl);

        if ($filter) {
            return sprintf('%s?%s', $base, $filter);
        }

        return $base;
    }

    public function getMyEntities(?string $filter = null): string
    {
        $base = sprintf('%s/my/entities', $this->apiUrl);

        if ($filter) {
            return sprintf('%s?%s', $base, $filter);
        }

        return $base;
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

    public function validateToken(): string
    {
        return sprintf('%s/token/validate', $this->apiUrl);
    }

    public function getOAuthToken(): string
    {
        return sprintf('%s/oauth2/token', $this->apiUrl);
    }
}
