<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class Basic
 * @package kalanis\kw_table\core\Table\Columns
 * Basic, simple column
 */
class Basic extends AColumn
{
    use TEscapedValue;

    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    protected function value(IRow $source, $property)
    {
        return $this->valueEscape($source->getValue($property));
    }
}
