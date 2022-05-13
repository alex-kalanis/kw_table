<?php

namespace kalanis\kw_table\core\Table\Internal;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Table\IColumn;
use kalanis\kw_table\core\Table\AStyle;


/**
 * Class Row
 * @package kalanis\kw_table\core\Table\Internal
 * Styled row in table
 */
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

    /**
     * @param IRow $source
     * @param $override
     * @return mixed
     * @throws ConnectException
     */
    protected function getOverrideValue(IRow $source, $override)
    {
        return $source->getValue($override);
    }
}
