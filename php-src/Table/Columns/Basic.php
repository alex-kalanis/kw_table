<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Basic
 * @package kalanis\kw_table\Table\Columns
 * Basic, simple column
 */
class Basic extends AColumn
{
    use EscapedValueTrait;

    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    protected function value(IRow $source, $property)
    {
        return $this->valueEscape($source->getValue($property));
    }
}
