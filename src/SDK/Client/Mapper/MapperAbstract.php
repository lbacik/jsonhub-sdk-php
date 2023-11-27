<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonException;
use JsonHub\SDK\Client\MapperInterface;

abstract class MapperAbstract implements MapperInterface
{
    abstract public function getType(): string;

    /** @throws JsonException */
    public function map(string $json): object
    {
        return $this->mapArray(json_decode($json, true, flags: JSON_THROW_ON_ERROR));
    }

    abstract public function mapArray(array $data): object;
}
