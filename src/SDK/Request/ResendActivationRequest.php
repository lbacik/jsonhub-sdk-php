<?php

declare(strict_types=1);

namespace JsonHub\SDK\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ResendActivationRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const EMAIL = 'email';

    protected Method $method = Method::POST;

    public function __construct(
        private array $payload = [],
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/users/resend-activation';
    }

    public function defaultBody(): array
    {
        return [
            self::EMAIL => $this->payload[self::EMAIL],
        ];
    }
}
