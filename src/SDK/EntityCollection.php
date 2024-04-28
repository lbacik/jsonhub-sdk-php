<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use Sushi\ObjectCollection;

class EntityCollection extends ObjectCollection
{
    protected static string $type = Entity::class;

    public function toArray(bool $recursively = true): array
    {
        return array_map(
            fn (Entity $entity) => $recursively ? (array) $entity : $entity,
            $this->getIterator()->getArrayCopy(),
        );
    }
}
