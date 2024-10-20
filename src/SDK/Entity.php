<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class Entity extends AbstractDocument
{
    public function __construct(
        public string|null $id,
        public string|null $iri,
        public string|null $slug,
        public array|null $data,
        public Definition|null $definition,
        public Entity|null $parent = null,
        public bool|null $private = null,
        public bool|null $owned = null,
    ) {
    }
}
