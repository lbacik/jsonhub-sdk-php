<?php

declare(strict_types=1);

namespace JsonHub\SDK\Factory;

use JsonHub\SDK\AbstractDocument;
use JsonHub\SDK\Definition;
use JsonHub\SDK\Entity;

class DefinitionFactory
{
    public static function create(
        string|null $id = null,
        string|null $slug = null,
        array|null $data = null,
        Entity|string|null $parentEntity = null,
    ): Definition {
        return new Definition(
            $id,
            $id ? AbstractDocument::asIri('definition', $id) : null,
            $slug,
            $data,
            match(true) {
                is_string($parentEntity) => EntityFactory::create(id: $parentEntity),
                default => $parentEntity,
            },
        );
    }
}
