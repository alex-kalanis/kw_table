<?php

namespace kalanis\kw_table\Connector\Filter\Arrays;


use kalanis\kw_table\Interfaces\Table\IRow;


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
        $this->dataSource->setArray(array_filter($this->dataSource->getArray(), function (IRow $item) use ($colName, $value) {
            return $item->getValue($colName) == $value;
        }));
        return $this;
    }
}
