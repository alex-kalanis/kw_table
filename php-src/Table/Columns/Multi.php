<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IColumn;
use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Multi
 * @package kalanis\kw_table\Table\Columns
 * Support for multi-columns
 */
class Multi extends AColumn
{
    /** @var string */
    protected $delimiter;

    /** @var IColumn[] */
    protected $columns = [];

    public function __construct($delimiter = ' ', $sourceName = 'primaryKey')
    {
        $this->delimiter = $delimiter;
        $this->sourceName = $sourceName;
    }

    /**
     * Add another column inside
     * @param IColumn $column
     */
    public function addColumn(IColumn $column): void
    {
        $this->columns[] = $column;
    }

    /**
     * @return IColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getValue(IRow $source)
    {
        $result = [];

        foreach ($this->columns as $column) {
            $result[] = $column->translate($source);
        }

        return implode($this->delimiter, $result);
    }
}
