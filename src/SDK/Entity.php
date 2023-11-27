<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class Entity
{
    public function __construct(
        public ?string $id,
        public ?string $iri,
        public string $slug,
        public array $data,
    ) {
    }
}
