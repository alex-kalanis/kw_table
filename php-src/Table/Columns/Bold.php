<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Bold
 * @package kalanis\kw_table\Table\Columns
 * Colum values will be bold
 */
class Bold extends AColumn
{
    protected $format = '';

    public function __construct($sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function getValue(IRow $source)
    {
        return '<strong>' . parent::getValue($source) . '</strong>';
    }
}
