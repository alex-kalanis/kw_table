<?php

namespace kalanis\kw_table\output_json;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_paging\Positions;
use kalanis\kw_table\core\Interfaces\Table\IFilterMulti;
use kalanis\kw_table\core\Table;


/**
 * Class JsonRenderer
 * @package kalanis\kw_table\output_json
 * Render output in json format
 */
class JsonRenderer extends Table\AOutput
{
    /** @var Positions|null */
    protected $positions = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        if ($table->getPager()) {
            $this->positions = new Positions($table->getPager()->getPager());
        }
    }

    /**
     * @throws ConnectException
     * @return string
     */
    public function render(): string
    {
        return strval(json_encode($this->renderData()));
    }

    /**
     * @throws ConnectException
     * @return array<string, array<string|int, string|int|float|bool|array<string|int, string|int|float|bool|null>|null>>
     */
    public function renderData(): array
    {
        return [
            'header' => $this->getHeaders(),
            'sorted' => $this->getSorters(),
            'filtered' => $this->getHeaderFilters(),
            'body' => $this->getCells(),
            'pager' => $this->getPager(),
        ];
    }

    /**
     * @return array<string|int, string>
     */
    protected function getHeaders(): array
    {
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            $line[$column->getSourceName()] = $column->getHeaderText();
        }
        return $line;
    }

    /**
     * @return array<string|int, array<string, string|int>>
     */
    protected function getSorters(): array
    {
        $order = $this->table->getOrder();
        if (!$order) {
            return [];
        }
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($order->isInOrder($column)) {
                $line[$column->getSourceName()] = [
                    'is_active' => intval($order->isActive($column)),
                    'direction' => $order->getActiveDirection($column),
                ];
            }
        }
        return $line;
    }

    /**
     * @return array<string|int, string|int|float|bool|null>
     */
    protected function getHeaderFilters(): array
    {
        $headerFilter = $this->table->getHeaderFilter();
        if (!$headerFilter) {
            return [];
        }

        $form = $this->table->getHeaderFilter()->getConnector();
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($column->hasHeaderFilterField()) {
                if ($column->getHeaderFilterField() instanceof IFilterMulti) {
                    // skip for now, there is no form with that name
                } else {
                    $line[$column->getSourceName()] = $form->getValue($column->getFilterName());
                }
            }
        }
        return $line;
    }

    /**
     * @throws ConnectException
     * @return array<int, array<string|int, string|int|float|bool|null>>
     */
    protected function getCells(): array
    {
        $cell = [];
        foreach ($this->table->getTableData() as $row) {
            /** @var Table\Internal\Row $row */
            $line = [];
            foreach ($row as $column) {
                /** @var Table\Columns\AColumn $column */
                $line[$column->getSourceName()] = $column->getValue($row->getSource());
            }
            $cell[] = $line;
        }
        return $cell;
    }

    /**
     * @return array<string, array<string, int>>
     */
    protected function getPager(): array
    {
        if (empty($this->positions)) {
            return [];
        }
        $pager = $this->positions->getPager();

        $pages = [];
        $pages['first'] = $this->positions->getFirstPage();
        $pages['prev'] = $this->positions->prevPageExists() ? $this->positions->getPrevPage() : $this->positions->getFirstPage() ;
        $pages['actual'] = $pager->getActualPage();
        $pages['next'] = $this->positions->nextPageExists() ? $this->positions->getNextPage() : $this->positions->getLastPage() ;
        $pages['last'] = $this->positions->getLastPage();

        $results = [];
        $results['from'] = $pager->getOffset() + 1;
        $results['to'] = min($pager->getOffset() + $pager->getLimit(), $pager->getMaxResults());
        $results['total'] = $pager->getMaxResults();

        return [
            'positions' => $pages,
            'results' => $results,
        ];
    }
}
