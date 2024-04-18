<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class Entity
{
    private const FIELD_TO_IRI = [
        'parent' => '/api/entities/%s',
        'definition' => '/api/definitions/%s',
    ];


    public function __construct(
        public string|null $id,
        public string|null $iri,
        public string|null $slug,
        public array|null $data,
        public string|null $definition,
        public string|null $parent = null,
        public bool|null $private = null,
        public bool|null $owned = null,
    ) {
    }

    public function toArray(): array
    {
        return array_reduce(
            array_keys(get_object_vars($this)),
            function ($carry, $key) {
                if ($this->{$key} !== null) {
                    if (array_key_exists($key, self::FIELD_TO_IRI)) {
                        $carry[$key] = sprintf(self::FIELD_TO_IRI[$key], $this->{$key});
                    } else {
                        $carry[$key] = $this->{$key};
                    }
                }
                return $carry;
            },
            []
        );
    }
}
