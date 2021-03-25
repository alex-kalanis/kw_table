<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Map
 * @package kalanis\kw_table\Table\Columns
 * Map content from source into something else defined by map
 */
class Map extends AColumn
{
    protected $map;
    protected $emptyValue;

    public function __construct(string $sourceName, array $map, string $emptyValue = '')
    {
        $this->sourceName = $sourceName;
        $this->map = $map;
        $this->emptyValue = $emptyValue;
    }

    public function getValue(IRow $source)
    {
        $value = (string) parent::getValue($source);

        if (isset($this->map[$value])) {
            return $this->map[$value];
        } elseif (empty($value)) {
            return $this->emptyValue;
        } else {
            return $value;
        }
    }
}
