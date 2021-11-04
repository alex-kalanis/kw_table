<?php

namespace kalanis\kw_table\Interfaces\Table;


use kalanis\kw_mapper\MapperException;


/**
 * Interface IRow
 * @package kalanis\kw_table\Interfaces\Table
 * Access rows in table
 */
interface IRow
{
    /**
     * @param string|int $property
     * @return mixed
     * @throws MapperException
     */
    public function getValue($property);

    /**
     * @param string|int $name
     * @return bool
     */
    public function __isset($name);
}
