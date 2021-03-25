<?php

namespace kalanis\kw_table\Table\Output;


use kalanis\kw_clipr\Output\PrettyTable;
use kalanis\kw_table\Table;


/**
 * Class Cli
 * @package kalanis\kw_table\Table\Output
 * Render output to Cli
 */
class Cli extends AOutput
{
    const HEADER_PARAM_SEPARATOR = ':';
    /** @var PrettyTable */
    protected $prettyTable = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->prettyTable = new PrettyTable();
    }

    public function render(): string
    {
        return implode(PHP_EOL, $this->renderData());
    }

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
        $sorter = $this->table->getSorter();
        $headerFilter = $this->table->getHeaderFilter();
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($headerFilter) {
                $line[] = $this->withSortDirection($sorter, $column) . $this->withFilter($column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            } else {
                $line[] = $this->withFilter($column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            }
            if ($sorter && $sorter->isSorted($column)) {
                $line[] = $this->withSortDirection($sorter, $column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            } else {
                $line[] = $column->getHeaderText();
            }
        }
        $this->prettyTable->setHeaders($line);
    }

    protected function withSortDirection(Table\Sorter $sorter, Table\Columns\AColumn $column): string
    {
        return ($sorter->isActive($column) ? '*' : '') . ($sorter->getDirection($column) == Table\Sorter::ORDER_ASC ? '^' : 'v');
    }

    protected function withFilter(Table\Columns\AColumn $column): string
    {
        return ($column->hasHeaderFilterField() ? '>' : '');
    }

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
