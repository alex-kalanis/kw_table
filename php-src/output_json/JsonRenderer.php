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
        if ($table->getPager() && $table->getPager()->getPager()) {
            $this->positions = new Positions($table->getPager()->getPager());
        }
    }

    /**
     * @return string
     * @throws ConnectException
     */
    public function render(): string
    {
        return json_encode($this->renderData());
    }

    /**
     * @return array
     * @throws ConnectException
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
        $sorter = $this->table->getOrder();
        if (!$sorter) {
            return [];
        }
        $line = [];
        foreach ($this->table->getColumns() as $column) {
            if ($sorter->isInOrder($column)) {
                $line[$column->getSourceName()] = [
                    'is_active' => intval($sorter->isActive($column)),
                    'direction' => $sorter->getActiveDirection($column),
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
     * @return array
     * @throws ConnectException
     */
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
