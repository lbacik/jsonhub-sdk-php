<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonHub\SDK\Entity;

class ParentEntityMapper extends MapperAbstract
{
    public function getType(): string
    {
        return Entity::class;
    }

    public function mapArray(array $data): object
    {
        $parent = $data['parent'] ?? null;

        return new Entity(
            $data['id'] ?? null,
            $data['@id'] ?? null,
            $data['slug'] ?? null,
            null,
            null,
        );
    }
}
