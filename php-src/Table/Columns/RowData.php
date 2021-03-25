<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class RowData
 * @package kalanis\kw_table\Table\Columns
 * Can work with all 'columns' including child columns etc.
 */
class RowData extends AColumn
{
    protected $callback;
    protected $columns;

    /**
     * @param string[] $columns
     * @param callable $callback
     */
    public function __construct(array $columns, callable $callback)
    {
        $this->sourceName = $columns[0];
        $this->columns = $columns;
        $this->callback = $callback;
    }

    public function getValue(IRow $source)
    {
        $rowData = [];
        foreach ($this->columns as $property) {
            $rowData[] = $source->getValue($property);
        }

        return call_user_func($this->callback, $rowData);
    }
}
