<?php

namespace kalanis\kw_table\Connector\Filter\Arrays;


/**
 * Class Exact
 * @package kalanis\kw_table\Connector\Filter\Arrays
 */
class Exact extends AType
{
    /**
     * @param string           $colName
     * @param string|int|float $value
     * @return $this
     */
    public function setFiltering($colName, $value)
    {
        $this->dataSource->setArray(array_filter($this->dataSource->getArray(), function ($item) use ($colName, $value) {
            return $item[$colName] == $value;
        }));
        return $this;
    }
}
