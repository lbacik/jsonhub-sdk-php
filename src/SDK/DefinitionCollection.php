<?php

declare(strict_types=1);

namespace JsonHub\SDK;

use Sushi\ObjectCollection;

class DefinitionCollection extends ObjectCollection
{
    protected static string $type = Definition::class;

    public function toArray(bool $recursively = true): array
    {
        return array_map(
            fn (Definition $item) => $recursively ? (array) $item : $item,
            $this->getIterator()->getArrayCopy(),
        );
    }
}
