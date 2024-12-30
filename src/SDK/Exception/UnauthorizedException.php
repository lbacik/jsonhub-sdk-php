<?php

declare(strict_types=1);

namespace JsonHub\SDK\Exception;

class UnauthorizedException extends \RuntimeException
{
    public static function create(): self
    {
        return new self('Unauthorized', 401);
    }
}
