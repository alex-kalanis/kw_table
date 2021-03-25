<?php

namespace kalanis\kw_table\Connector\Filter\Arrays;


/**
 * Class Contains
 * @package kalanis\kw_table\Connector\Filter\Arrays
 */
class Contains extends AType
{
    /**
     * @param string           $colName
     * @param string|int|float $value
     * @return $this
     */
    public function setFiltering($colName, $value)
    {
        $this->dataSource->setArray(array_filter($this->dataSource->getArray(), function ($item) use ($colName, $value) {
            return preg_match('#' . preg_quote($value, '#') . '#', $item[$colName]);
        }));
        return $this;
    }
}
