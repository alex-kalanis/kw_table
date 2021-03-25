<?php

namespace kalanis\kw_table\Table\Output;


use kalanis\kw_paging\Positions;
use kalanis\kw_table\Table;


/**
 * Class Json
 * @package kalanis\kw_table\Table\Output
 * Render output in json format
 */
class Json extends AOutput
{
    /** @var Positions|null */
    protected $positions = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        if ($table->getOutputPager() && $table->getOutputPager()->getPager()) {
            $this->positions = new Positions($table->getOutputPager()->getPager());
        }
    }

    public function render(): string
    {
        return json_encode($this->renderData());
    }

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

    protected function getHeaders(): array
    {
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            $line[$column->getSourceName()] = $column->getHeaderText();
        }
        return $line;
    }

    protected function getSorters(): array
    {
        $sorter = $this->table->getSorter();
        if (!$sorter) {
            return [];
        }
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($sorter->isSorted($column)) {
                $line[$column->getSourceName()] = [
                    'is_active' => intval($sorter->isActive($column)),
                    'direction' => $sorter->getDirection($column),
                ];
            }
        }
        return $line;
    }

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
                $line[$column->getSourceName()] = $form->getValue($column->getSourceName());
            }
        }
        return $line;
    }

    protected function getCells(): array
    {
        $cell = [];
        foreach ($this->table->getTableData() as $row) {
            $line = [];
            foreach ($row as $column) {
                /** @var Table\Columns\AColumn $column */
                $line[$column->getSourceName()] = $column->getValue($row->getSource());
            }
            $cell[] = $line;
        }
        return $cell;
    }

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
