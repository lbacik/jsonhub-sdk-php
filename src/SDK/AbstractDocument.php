<?php

declare(strict_types=1);

namespace JsonHub\SDK;

readonly class AbstractDocument
{
    private const FIELD_TO_IRI = [
        'parent' => '/api/entities/%s',
        'parentEntity' => '/api/entities/%s',
        'definition' => '/api/definitions/%s',
    ];

    public function toArray(): array
    {
        return array_reduce(
            array_keys(get_object_vars($this)),
            function ($carry, $key) {
                if ($this->{$key} !== null) {
                    if (
                        array_key_exists($key, self::FIELD_TO_IRI)
                        && str_starts_with($this->{$key}, substr(self::FIELD_TO_IRI[$key], 0, -2)) === false
                    ) {
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

    public static function asIri(string $iriKey, string $uuid): string
    {
        return sprintf(self::FIELD_TO_IRI[$iriKey], $uuid);
    }
}