<?php

declare(strict_types=1);

namespace JsonHub\SDK\Request;

use Saloon\Traits\Body\HasJsonBody;

class ForgotPasswordResetRequest extends AbstractJsonRequest
{
    use HasJsonBody;

    public const PASSWORD = 'password';

    public const TOKEN = 'token';

    public function resolveEndpoint(): string
    {
        return '/api/users/reset-password';
    }

    public function defaultBody(): array
    {
        return [
            self::PASSWORD => $this->payload[self::PASSWORD],
            self::TOKEN => $this->payload[self::TOKEN],
        ];
    }
}
