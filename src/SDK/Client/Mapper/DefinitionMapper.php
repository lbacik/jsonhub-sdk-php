<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client\Mapper;

use JsonHub\SDK\Definition;

class DefinitionMapper extends MapperAbstract
{
    public function getType(): string
    {
        return Definition::class;
    }

    public function mapArray(array $data): object
    {
        return new Definition(
            slug: $data['slug'] ?? '',
            data: $data['jsonSchema'],
        );
    }
}
