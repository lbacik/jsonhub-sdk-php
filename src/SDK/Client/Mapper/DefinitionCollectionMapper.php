<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonHub\SDK\DefinitionCollection;

class DefinitionCollectionMapper extends MapperAbstract
{
    public function __construct(
        private readonly DefinitionMapper $definitionMapper,
    ) {
    }

    public function getType(): string
    {
        return DefinitionCollection::class;
    }

    public function mapArray(array $data): DefinitionCollection
    {
        return new DefinitionCollection(
            array_map(
                fn (array $item) => $this->definitionMapper->mapArray($item),
                $data,
            )
        );
    }
}
