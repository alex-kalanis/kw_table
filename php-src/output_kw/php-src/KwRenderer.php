<?php

namespace kalanis\kw_table\output_kw;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\TableException;


/**
 * Class KwRenderer
 * @package kalanis\kw_table\output_kw
 * Render output in html templates from kw_template
 */
class KwRenderer extends Table\AOutput
{
    /** @var Html\TableBase */
    protected $templateBase = null;
    /** @var Html\TableCell */
    protected $templateCell = null;
    /** @var Html\TableFoot */
    protected $templateFoot = null;
    /** @var Html\TableHead */
    protected $templateHead = null;
    /** @var Html\TableHeadSorted */
    protected $templateHeadSorted = null;
    /** @var Html\TableRow */
    protected $templateRow = null;
    /** @var Html\TableScript */
    protected $templateScript = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->templateBase = new Html\TableBase();
        $this->templateCell = new Html\TableCell();
        $this->templateFoot = new Html\TableFoot();
        $this->templateHead = new Html\TableHead();
        $this->templateHeadSorted = new Html\TableHeadSorted();
        $this->templateRow = new Html\TableRow();
        $this->templateScript = new Html\TableScript();
    }

    /**
     * @throws ConnectException
     * @throws RenderException
     * @throws TableException
     * @return string
     */
    public function render(): string
    {
        $this->renderPagers();
        $this->renderFilter();
        $this->renderScript();
        return $this->templateBase->setData(
            $this->getCells(),
            $this->getHeader(),
            $this->getHeadFilter(),
            $this->getFootFilter(),
            $this->table->getClassesInString()
        )->render();
    }

    protected function renderPagers(): void
    {
        if (empty($this->table->getPager())) {
            return;
        }
        $paging = $this->table->getPager();
        if ($this->table->showPagerOnHead()) {
            $this->templateBase->addPagerHead($paging->render());
        }
        if ($this->table->showPagerOnFoot()) {
            $this->templateBase->addPagerFoot($paging->render());
        }
    }

    /**
     * @throws RenderException
     */
    protected function renderFilter(): void
    {
        $headerFilter = $this->table->getHeaderFilter();
        $footerFilter = $this->table->getFooterFilter();
        $this->templateBase->addFilter(
            $headerFilter ? $headerFilter->renderStart() : ($footerFilter ? $footerFilter->renderStart() : ''),
            $headerFilter ? $headerFilter->renderEnd() : ($footerFilter ? $footerFilter->renderEnd() : '')
        );
    }

    protected function renderScript(): void
    {
        $headerFilter = $this->table->getHeaderFilter();
        $footerFilter = $this->table->getFooterFilter();
        $formName = $this->table->getFormName();
        if ($formName && ($headerFilter || $footerFilter)) {
            $this->templateBase->addScript(
                $this->templateScript->reset()->setData($formName)->render()
            );
        }
    }

    /**
     * @throws ConnectException
     * @throws TableException
     * @return string
     */
    protected function getCells(): string
    {
        $cell = [];
        foreach ($this->table->getTableData() as $row) {
            /** @var Table\Internal\Row $row */
            $this->templateRow->reset()->setData($row->getCellStyle($row->getSource()));
            foreach ($row as $column) {
                /** @var Table\Columns\AColumn $column */
                $this->templateRow->addCell($this->templateCell->reset()->setData(
                    $column->translate($row->getSource()),
                    $column->getCellStyle($row->getSource())
                )->render());
            }
            $cell[] = $this->templateRow->render();
        }
        return implode('', $cell);
    }

    protected function getHeader(): string
    {
        $order = $this->table->getOrder();
        $this->templateRow->reset()->setData();
        foreach ($this->table->getColumns() as $column) {
            if ($order && $order->isInOrder($column)) {
                $this->templateRow->addCell($this->templateHeadSorted->reset()->setData(
                    $order->getHeaderText($column), $order->getHref($column)
                )->render());
            } else {
                $this->templateRow->addCell($this->templateHead->reset()->setData(
                    $column->getHeaderText()
                )->render());
            }
        }
        return $this->templateRow->render();
    }

    /**
     * @throws RenderException
     * @throws TableException
     * @return string
     */
    protected function getHeadFilter(): string
    {
        $headerFilter = $this->table->getHeaderFilter();
        if (!$headerFilter) {
            return '';
        }

        $this->templateRow->reset()->setData();
        foreach ($this->table->getColumns() as $column) {
            $this->templateRow->addCell($this->templateHead->reset()->setData(
                $column->hasHeaderFilterField() ? $headerFilter->renderHeaderInput($column) : ''
            )->render());
        }
        return $this->templateRow->render();
    }

    /**
     * @throws RenderException
     * @throws TableException
     * @return string
     */
    protected function getFootFilter(): string
    {
        $footerFilter = $this->table->getFooterFilter();
        if (!$footerFilter) {
            return '';
        }

        $this->templateRow->reset()->setData();
        foreach ($this->table->getColumns() as $column) {
            $this->templateRow->addCell($this->templateCell->reset()->setData(
                $column->hasFooterFilterField() ? $footerFilter->renderFooterInput($column) : ''
            )->render());
        }
        return $this->templateRow->render();
    }
}
