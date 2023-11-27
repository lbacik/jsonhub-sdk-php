<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client;

use InvalidArgumentException;
use Sushi\ObjectCollection;

class MapperService extends ObjectCollection
{
    protected static string $type = MapperInterface::class;

    public function getMapperFor(string $type): MapperInterface
    {
        foreach ($this->getIterator() as $mapper) {
            if ($mapper->getType() === $type) {
                return $mapper;
            }
        }

        throw new InvalidArgumentException(sprintf(
            'Mapper for type "%s" not found.',
            $type,
        ));
    }
}
