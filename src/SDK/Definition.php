<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class Definition
{
    public function __construct(
        public string $id,
        public string $slug,
        public array $data,
    ) {
    }
}
