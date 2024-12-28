<?php

declare(strict_types=1);

namespace JsonHub\SDK\Factory;

use JsonHub\SDK\AbstractDocument;
use JsonHub\SDK\Definition;
use JsonHub\SDK\Entity;

class EntityFactory
{
    public static function create(
        string|null $id = null,
        string|null $slug = null,
        array|null $data = null,
        Definition|string|null $definition = null,
        Entity|string|null $parent = null,
        bool|null $private = null,
    ): Entity {
        return new Entity(
            $id,
            $id ? AbstractDocument::asIri('entity', $id) : null,
            $slug,
            $data,
            match(true) {
                is_string($definition) => DefinitionFactory::create(id: $definition),
                default => $definition,
            },
            match(true) {
                is_string($parent) => EntityFactory::create(id: $parent),
                default => $parent,
            },
            $private,
        );
    }
}
