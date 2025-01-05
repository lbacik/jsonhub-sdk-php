<?php

namespace JsonHub\SDK\Request;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ActivationRequest extends Request
{
    public const TOKEN = 'token';

    protected Method $method = Method::GET;

    private string $token;

    public function __construct(
        private array $payload = [],
    ) {
        $this->token = $this->payload[static::TOKEN] ?? '';
    }

    public function resolveEndpoint(): string
    {
        return '/activate/' . $this->token;
    }
}