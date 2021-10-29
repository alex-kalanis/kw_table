<?php

namespace kalanis\kw_table\Connector\Rows;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Arrays
 * @package kalanis\kw_table\Connector\Rows
 */
class Arrays implements IRow
{
    protected $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function getValue($property)
    {
        return $this->array[$property];
    }

    public function __isset($name)
    {
        return isset($this->array[$name]);
    }
}
