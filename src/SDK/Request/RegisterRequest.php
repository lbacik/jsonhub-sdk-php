<?php

declare(strict_types=1);

namespace JsonHub\SDK\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class RegisterRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const FIELD_EMAIL = 'email';

    public const FIELD_PASSWORD = 'password';

    public const FIELD_ACTIVATION_URL = 'activationUrl';

    protected Method $method = Method::POST;

    public function __construct(
        private array $payload = [],
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/users';
    }

    protected function defaultBody(): array
    {
        $result = [
            'email' => $this->payload[self::FIELD_EMAIL],
            'password' => $this->payload[self::FIELD_PASSWORD],
        ];

        if (isset($this->payload[self::FIELD_ACTIVATION_URL])) {
            $result['activationUrl'] = $this->payload[self::FIELD_ACTIVATION_URL];
        }

        return $result;
    }
}
