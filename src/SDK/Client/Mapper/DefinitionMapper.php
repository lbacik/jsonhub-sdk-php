<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonHub\SDK\Definition;

class DefinitionMapper extends MapperAbstract
{
    public function __construct(
        private readonly ParentEntityMapper $parentEntityMapper,
    ) {
    }

    public function getType(): string
    {
        return Definition::class;
    }

    public function mapArray(array $data): object
    {
        $parentEntity = $data['parentEntity'] ?? null;

        return new Definition(
            id: $data['id'] ?? null,
            iri: $data['@id'] ?? null,
            slug: $data['slug'] ?? null,
            data: $data['jsonSchema'] ?? null,
            parentEntity: $parentEntity ? $this->parentEntityMapper->mapArray($parentEntity) : null,
        );
    }
}
