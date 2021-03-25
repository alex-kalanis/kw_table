<?php

namespace kalanis\kw_table\Connector\Rows;


use kalanis\kw_table\Interfaces\Table\IRow;


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
}
