<?php

namespace kalanis\kw_table\Table\Internal;


use kalanis\kw_table\Interfaces\Table\IColumn;
use kalanis\kw_table\Interfaces\Table\IRow;
use kalanis\kw_table\Table\AStyle;


class Row extends AStyle
{
    /** @var IColumn[] */
    protected $columns = [];
    /** @var IRow */
    protected $sourceData = null;

    /**
     * Add column with entry into the stack
     * @param IColumn $column
     */
    public function addColumn(IColumn $column): void
    {
        $this->columns[] = $column;
    }

    public function setSource(IRow $source): void
    {
        $this->sourceData = $source;
    }

    public function getSource(): ?IRow
    {
        return $this->sourceData;
    }

    protected function getIterableName(): string
    {
        return 'columns';
    }

    protected function getOverrideValue(IRow $source, $override)
    {
        return $source->getValue($override);
    }
}
