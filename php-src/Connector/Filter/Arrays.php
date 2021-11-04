<?php

namespace kalanis\kw_table\Connector\Filter;


use ArrayAccess;
use Countable;
use kalanis\kw_table\Connector\Rows;


/**
 * Class Arrays
 * @package kalanis\kw_table\Connector\Filter
 * Class for updating arrays via reference
 */
class Arrays implements ArrayAccess, Countable
{
    protected $array;

    /**
     * @param string[]|int[]|bool[]|Rows\Arrays[] $array
     */
    public function __construct(array &$array)
    {
        $this->array = &$array;
    }

    /**
     * @return string[]|int[]|bool[]|Rows\Arrays[]
     */
    public function &getArray()
    {
        return $this->array;
    }

    /**
     * @param string[]|int[]|bool[] $array
     * @return $this
     */
    public function setArray($array)
    {
        $this->array = $array;
        return $this;
    }

    public function resetArray()
    {
        $this->array = [];
        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->array[$offset] : null ;
    }

    public function offsetSet($offset, $value)
    {
        $this->array[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->array[$offset]);
        }
    }

    public function count()
    {
        return count($this->array);
    }
}
