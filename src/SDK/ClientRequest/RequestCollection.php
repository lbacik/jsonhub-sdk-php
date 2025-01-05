<?php

declare(strict_types=1);

namespace JsonHub\SDK\ClientRequest;

use Saloon\Http\Request;
use Sushi\ObjectCollection;

class RequestCollection extends ObjectCollection
{
    protected static string $type = RequestAdapter::class;

    public function getRequest(string $name, array|null $payload = null): Request
    {
        /** @var RequestAdapter $request */
        foreach ($this as $request) {
            if ($request->isRequestClass($name)) {
                return $request->instantiate($payload);
            }
        }

        throw new \RuntimeException('Request not found in collection');
    }
}