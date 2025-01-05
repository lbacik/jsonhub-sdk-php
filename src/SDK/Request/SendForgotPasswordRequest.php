<?php

declare(strict_types=1);

namespace JsonHub\SDK\Request;

class SendForgotPasswordRequest extends AbstractJsonRequest
{
    public const EMAIL = 'email';

    public const RESET_PASSWORD_LINK = 'resetPasswordLink';

    public function resolveEndpoint(): string
    {
        return '/api/users/send-reset-password';
    }

    public function defaultBody(): array
    {
        $result = [
            self::EMAIL => $this->payload[self::EMAIL],
        ];

        if ($this->payload[self::RESET_PASSWORD_LINK]) {
            $result[self::RESET_PASSWORD_LINK] = $this->payload[self::RESET_PASSWORD_LINK];
        }

        return $result;
    }
}
