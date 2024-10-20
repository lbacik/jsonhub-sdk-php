<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class Definition extends AbstractDocument
{
    public function __construct(
        public string|null $id,
        public string|null $iri,
        public string|null $slug,
        public array|null $data,
        public Entity|null $parentEntity = null,
    ) {
    }
}
