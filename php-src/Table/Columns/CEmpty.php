<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class CEmpty
 * @package kalanis\kw_table\Table\Columns
 * Empty column
 */
class CEmpty extends AColumn
{
    public function getValue(IRow $source)
    {
        return '';
    }
}
