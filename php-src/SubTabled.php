<?php

namespace kalanis\kw_table;


use kalanis\kw_table\Table\Output\AOutput;


/**
 * Class SubTabled
 * @package kalanis\kw_table
 * Allow to render sub-table as row
 * This one cannot be rendered in CLI or JSON
 */
class SubTabled extends Table
{
    /** @var Table\Rows\TableRow[] */
    private $rowCallback = [];

    final public function setOutput(AOutput $output)
    {
        // cannot set
        $this->output = new Table\Output\HtmlTemplates($this);
    }

    /**
     * Update columns to readable format
     * @throws TableException
     */
    public function translateData(): void
    {
        if (is_null($this->dataSource)) {
            throw new TableException('Cant create table from empty dataset');
        }

        if (empty($this->columns)) {
            throw new TableException('You need to define at least one column');
        }

        foreach ($this->dataSource as $source) {
            $rowData = new Table\Internal\Row();
            $rowData->setSource($source);

            foreach ($this->callRows as $call) {
                call_user_func_array([$rowData, $call->getFunctionName()], $call->getFunctionArgs());
            }

            foreach ($this->columns as $column) {
                $col = clone $column;
                $rowData->addColumn($col);
            }

            $this->tableData[] = $rowData;

            foreach ($this->rowCallback as $call) {
                $callback = call_user_func_array($call->getFunctionName(), array_merge(['rowData' => $rowData], $call->getFunctionArgs()));
                if ($callback instanceof Table || $callback instanceof Table\Internal\Row) {
                    $this->tableData[] = $callback;
                } else {
                    throw new TableException('Row callback needs to return \kalanis\kw_table\Table or \kalanis\kw_table\Table\Internal\Row');
                }
            }
        }
    }

    protected function addRowCallback(callable $function, array $arguments = [])
    {
        $this->rowCallback[] = new Table\Rows\TableRow($function, $arguments);
    }
}
