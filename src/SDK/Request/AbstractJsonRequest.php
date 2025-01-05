<?php

declare(strict_types=1);

namespace JsonHub\SDK\Request;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

abstract class AbstractJsonRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload = [],
    ) {
    }

    abstract public function resolveEndpoint(): string;

    abstract public function defaultBody(): array;
}
