<?php

namespace kalanis\kw_table\Connector\Filter\Search;


/**
 * Class Exact
 * @package kalanis\kw_table\Connector\Filter\Search
 */
class Exact extends AType
{
    public function setFiltering(string $colName, $value)
    {
        if ('' !== $value) {
            $this->search->exact($colName, $value);
        }
        return $this;
    }
}
