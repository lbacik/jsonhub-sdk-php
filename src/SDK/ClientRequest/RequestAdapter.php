<?php

declare(strict_types=1);

namespace JsonHub\SDK\ClientRequest;

use Saloon\Http\Request;

readonly class RequestAdapter
{
    public function __construct(
        private string $requestClass,
    ) {
    }

    public function isRequestClass(string $class): bool
    {
        return $class === $this->requestClass;
    }

    public function instantiate(array|null $payload = null): Request
    {
        return new $this->requestClass($payload);
    }
}
