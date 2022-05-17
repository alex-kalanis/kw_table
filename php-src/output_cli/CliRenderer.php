<?php

namespace kalanis\kw_table\output_cli;


use kalanis\kw_clipr\Output\PrettyTable;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table;


/**
 * Class CliRenderer
 * @package kalanis\kw_table\output_cli
 * Render output to Cli
 */
class CliRenderer extends Table\AOutput
{
    const HEADER_PARAM_SEPARATOR = ':';
    /** @var PrettyTable */
    protected $prettyTable = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->prettyTable = new PrettyTable();
    }

    /**
     * @return string
     * @throws ConnectException
     */
    public function render(): string
    {
        return implode(PHP_EOL, $this->renderData());
    }

    /**
     * @return array
     * @throws ConnectException
     */
    public function renderData(): array
    {
        $this->fillHeaders();
        $this->fillCells();

        $lines = [];
        $lines[] = $this->prettyTable->getSeparator();
        $lines[] = $this->prettyTable->getHeader();
        $lines[] = $this->prettyTable->getSeparator();
        foreach ($this->prettyTable as $row) {
            $lines[] = $row;
        }
        $lines[] = $this->prettyTable->getSeparator();
        $lines[] = $this->getPager();
        return $lines;
    }

    protected function fillHeaders(): void
    {
        $sorter = $this->table->getOrder();
        $headerFilter = $this->table->getHeaderFilter();
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($headerFilter) {
                $line[] = $this->withSortDirection($sorter, $column) . $this->withFilter($column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            } else {
                $line[] = $this->withFilter($column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            }
            if ($sorter && $sorter->isInOrder($column)) {
                $line[] = $this->withSortDirection($sorter, $column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            } else {
                $line[] = $column->getHeaderText();
            }
        }
        $this->prettyTable->setHeaders($line);
    }

    protected function withSortDirection(Table\Order $sorter, Table\Columns\AColumn $column): string
    {
        return ($sorter->isActive($column) ? '*' : '') . ($sorter->getDirection($column) == Table\Order::ORDER_ASC ? '^' : 'v');
    }

    protected function withFilter(Table\Columns\AColumn $column): string
    {
        return ($column->hasHeaderFilterField() ? '>' : '');
    }

    /**
     * @throws ConnectException
     */
    protected function fillCells(): void
    {
        foreach ($this->table->getTableData() as $row) {
            $line = [];
            foreach ($row as $column) {
                /** @var Table\Columns\AColumn $column */
                $line[] = $column->getValue($row->getSource());
            }
            $this->prettyTable->setDataLine($line);
        }
    }

    protected function getPager(): string
    {
        return $this->table->getOutputPager() ? $this->table->getOutputPager()->render() : '' ;
    }
}