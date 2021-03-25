<?php

namespace kalanis\kw_table\Connector\Filter\Search;


use kalanis\kw_mapper\MapperException;


/**
 * Class Range
 * @package kalanis\kw_table\Connector\Filter\Search
 */
class Range extends AType
{
    public function setFiltering(string $colName, $value)
    {
        if (!is_array($value) || empty($value)) {
            throw new MapperException('Value must be an array of two values with keys 0 and 1.');
        }

        if (!empty($value[0])) {
            $this->search->from($colName, $value[0]);
        }
        if (!empty($value[1])) {
            $this->search->to($colName, $value[1]);
        }

        return $this;
    }
}
