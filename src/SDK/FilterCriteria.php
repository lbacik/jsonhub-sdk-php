<?php

declare(strict_types=1);

namespace JsonHub\SDK;

class FilterCriteria
{
    public function __construct(
        public readonly int|null $page = null,
        public readonly int|null $limit = null,
        public readonly string|null $definitionUuid = null,
        public readonly string|null $parentUuid = null,
        public readonly string|null $slugSearchTerm = null,
        public readonly string|null $dataSearchTerm = null,
    ) {
    }

    public function generateQueryString(): string
    {
        $queryParamsArray = [];

        $this->page && $queryParamsArray['page'] = $this->page;
        $this->limit && $queryParamsArray['limit'] = $this->limit;
        $this->definitionUuid && $queryParamsArray['definition'] = $this->definitionUuid;
        $this->parentUuid && $queryParamsArray['parent'] = $this->parentUuid;
        $this->slugSearchTerm && $queryParamsArray['slug'] = $this->slugSearchTerm;
        $this->dataSearchTerm && $queryParamsArray['data'] = $this->dataSearchTerm;

        return http_build_query($queryParamsArray);
    }
}
