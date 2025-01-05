<?php

declare(strict_types=1);

namespace JsonHub\SDK\ClientRequest;

use Saloon\Http\Connector;

class JsonHubConnector extends Connector
{
    public function __construct(
        private string $url,
    ) {
    }

    public function resolveBaseUrl(): string
    {
        return $this->url;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/ld+json',
        ];
    }
}