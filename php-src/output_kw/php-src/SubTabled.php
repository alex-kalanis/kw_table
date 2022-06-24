<?php

namespace kalanis\kw_table\output_kw;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\TableException;


/**
 * Class SubTabled
 * @package kalanis\kw_table\output_kw
 * Allow to render sub-table as row
 * This one cannot be rendered in CLI or JSON
 */
class SubTabled extends Table
{
    /** @var Table\Rows\TableRow[] */
    private $rowCallback = [];

    final public function setOutput(Table\AOutput $output): void
    {
        // cannot be set
        $this->output = new KwRenderer($this);
    }

    /**
     * Update columns to readable format
     * @throws ConnectException
     * @throws TableException
     */
    public function translateData(): void
    {
        if (is_null($this->dataSetConnector)) {
            throw new TableException('Cannot create table from empty dataset');
        }

        if (empty($this->columns)) {
            throw new TableException('You need to define at least one column');
        }

        $this->applyFilter();
        $this->applyOrder();
        $this->applyPager();

        $this->dataSetConnector->fetchData();

        foreach ($this->dataSetConnector as $source) {
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

    /**
     * @param callable $function
     * @param string[] $arguments styles
     */
    protected function addRowCallback($function, array $arguments = []): void
    {
        $this->rowCallback[] = new Table\Rows\TableRow($function, $arguments);
    }
}
