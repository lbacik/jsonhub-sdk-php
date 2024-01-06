<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonHub\SDK\EntityCollection;

class EntityCollectionMapper extends MapperAbstract
{
    public function __construct(
        private readonly EntityMapper $entityMapper,
    ) {
    }

    public function getType(): string
    {
        return EntityCollection::class;
    }

    public function mapArray(array $data): EntityCollection
    {
        return new EntityCollection(
            array_map(
                fn (array $item) => $this->entityMapper->mapArray($item),
                $data,
            )
        );
    }
}
