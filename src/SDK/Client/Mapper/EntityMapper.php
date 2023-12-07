<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonHub\SDK\Entity;

class EntityMapper extends MapperAbstract
{
    public function getType(): string
    {
        return Entity::class;
    }

    public function mapArray(array $data): object
    {
        return new Entity(
            $data['id'] ?? null,
            $data['@id'] ?? null,
            $data['slug'] ?? '',
            $data['data'],
            $data['definition'] ?? null,
        );
    }
}
