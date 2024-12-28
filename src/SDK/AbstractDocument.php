<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class AbstractDocument
{
    private const FIELD_TO_IRI = [
        'entity' => '/api/entities/%s',
        'parent' => '/api/entities/%s',
        'parentEntity' => '/api/entities/%s',
        'definition' => '/api/definitions/%s',
    ];

    protected const UPDATABLE_FIELDS = [];

    public function toArray(): array
    {
        return array_reduce(
            array_keys(get_object_vars($this)),
            function ($carry, $key) {
                if ($this->{$key} !== null) {
                    if (array_key_exists($key, self::FIELD_TO_IRI)) {
                        $carry[$key] = $this->{$key}?->iri;
                    } else {
                        $carry[$key] = $this->{$key};
                    }
                }
                return $carry;
            },
            []
        );
    }

    public function updatableToArray(): array
    {
        return array_filter(
            $this->toArray(),
            fn ($key): bool => in_array($key, static::UPDATABLE_FIELDS),
            ARRAY_FILTER_USE_KEY,
        );
    }

    public static function asIri(string $iriKey, string $uuid): string
    {
        return sprintf(self::FIELD_TO_IRI[$iriKey], $uuid);
    }
}