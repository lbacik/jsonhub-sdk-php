<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class Entity
{
    public function __construct(
        public string|null $id,
        public string|null $iri,
        public string $slug,
        public array $data,
        public string|null $definition,
        public string|null $parent = null,
        public bool $private = false,
        public bool $owned = false,
    ) {
    }
}
