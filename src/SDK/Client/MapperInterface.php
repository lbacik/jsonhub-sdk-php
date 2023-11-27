<?php

declare(strict_types=1);

namespace JsonHub\SDK\Client;

interface MapperInterface
{
    public function getType(): string;
    public function map(string $json): object;
}
