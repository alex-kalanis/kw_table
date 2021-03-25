<?php

namespace kalanis\kw_table\Connector\Filter;


/**
 * Class Arrays
 * @package kalanis\kw_table\Connector\Filter
 * Class for updating arrays via reference
 */
class Arrays
{
    protected $array;

    /**
     * @param string[]|int[]|bool[] $array
     */
    public function __construct(array &$array)
    {
        $this->array = &$array;
    }

    /**
     * @return string[]|int[]|bool[]
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
}
