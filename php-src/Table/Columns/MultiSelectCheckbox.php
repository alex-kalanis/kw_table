<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class MultiSelectCheckbox
 * @package kalanis\kw_table\Table\Columns
 */
class MultiSelectCheckbox extends AColumn
{
    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function getValue(IRow $source)
    {
        return '<input type="checkbox" name="multiselect[' . parent::getValue($source) . ']" class="multiselect">';
    }
}
