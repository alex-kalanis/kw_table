<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Table\IColumn;


/**
 * Class Multi
 * @package kalanis\kw_table\core\Table\Columns
 * Support for multi-columns
 */
class Multi extends AColumn
{
    /** @var string */
    protected $delimiter;
    /** @var IColumn[] */
    protected $columns = [];

    /**
     * @param string $delimiter
     * @param string|int $sourceName
     */
    public function __construct(string $delimiter = ' ', $sourceName = 'primaryKey')
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

    public function getValue(IRow $source)
    {
        $result = [];

        foreach ($this->columns as $column) {
            $result[] = $column->translate($source);
        }

        return implode($this->delimiter, $result);
    }

    public function canOrder(): bool
    {
        return false;
    }
}
