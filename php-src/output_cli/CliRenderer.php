<?php

namespace kalanis\kw_table\output_cli;


use kalanis\kw_clipr\Output\PrettyTable;
use kalanis\kw_table\core\Interfaces;
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
     */
    public function render(): string
    {
        return implode(PHP_EOL, $this->renderData());
    }

    /**
     * @return array<string>
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
            $lines[] = strval($row);
        }
        $lines[] = $this->prettyTable->getSeparator();
        $lines[] = $this->getPager();
        return $lines;
    }

    protected function fillHeaders(): void
    {
        $order = $this->table->getOrder();
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($order && $order->isInOrder($column)) {
                $line[] = $this->withOrderDirection($order, $column) . $this->withFilter($column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            } else {
                $line[] = $this->withFilter($column) . static::HEADER_PARAM_SEPARATOR . $column->getHeaderText();
            }
        }
        $this->prettyTable->setHeaders($line);
    }

    protected function withOrderDirection(Table\Order $order, Interfaces\Table\IColumn $column): string
    {
        return Table\Order::ORDER_ASC == $order->getActiveDirection($column)
            ? ($order->isActive($column) ? '*^' : 'v')
            : ($order->isActive($column) ? '*v' : '^')
        ;
    }

    protected function withFilter(Interfaces\Table\IColumn $column): string
    {
        return ($column->hasHeaderFilterField() ? '>' : '');
    }

    protected function fillCells(): void
    {
        foreach ($this->table->getTableData() as $row) {
            /** @var Table\Internal\Row $row */
            $line = [];
            foreach ($row as $column) {
                /** @var Interfaces\Table\IColumn $column */
                $line[] = $column->getValue($row->getSource());
            }
            $this->prettyTable->setDataLine($line);
        }
    }

    protected function getPager(): string
    {
        return $this->table->getPager() ? PHP_EOL . $this->table->getPager()->render() . PHP_EOL : '' ;
    }

    public function getTableEngine(): PrettyTable
    {
        return $this->prettyTable;
    }
}
