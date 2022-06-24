<?php

namespace kalanis\kw_table\core;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_paging\Interfaces\IOutput;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\Interfaces\Form\IFilterForm;
use kalanis\kw_table\core\Interfaces\Table\IColumn;
use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class Table
 * @package kalanis\kw_table\core
 * Main table render
 */
class Table
{
    const PAGER_LIMIT_DEFAULT = 30;

    /** @var IIterableConnector|null */
    protected $dataSetConnector = null;

    /** @var IColumn[] */
    protected $columns = [];

    /** @var Table\Rows\ARow[] */
    protected $callRows = [];

    /** @var string[] */
    protected $classes = ['table', 'table-bordered', 'table-striped', 'table-hover', 'table-condensed', 'bootstrap-datatable', 'listtable'];

    /** @var IOutput|null */
    protected $pager = null;

    /** @var Table\Order|null */
    protected $order = null;

    /** @var Table\Filter|null */
    protected $headerFilter = null;

    /** @var Table\Filter|null */
    protected $footerFilter = null;

    /** @var Table\AOutput|null */
    protected $output = null;

    /** @var Table\Internal\Row[]|Table[] */
    protected $tableData = [];

    /** @var array<string, string> */
    protected $defaultHeaderFilterFieldAttributes = [];

    /** @var array<string, string>  */
    protected $defaultFooterFilterFieldAttributes = [];

    /** @var bool */
    protected $showPagerOnHead = false;

    /** @var bool */
    protected $showPagerOnFoot = true;

    /**
     * @param IIterableConnector|null $dataSetConnector
     */
    public function __construct(IIterableConnector $dataSetConnector = null)
    {
        if (!is_null($dataSetConnector)) {
            $this->addDataSetConnector($dataSetConnector);
        }
    }

    /**
     * Add external function to row for processing data
     * @param string $function
     * @param string[] $arguments
     * @return mixed|null|void
     */
    public function __call($function, $arguments)
    {
        if (preg_match('/^row(.*)$/', $function, $matches)) {
            $this->callRows[] = new Table\Rows\FunctionRow($matches[1], $arguments);
        }
    }

    /**
     * Add style class to whole row depending on the rule
     * @param string $class
     * @param IRule  $rule
     * @param string $cell
     */
    public function rowClass(string $class, IRule $rule, $cell): void
    {
        $this->callRows[] = new Table\Rows\ClassRow($class, $rule, $cell);
    }

    public function addOrder(Table\Order $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function addHeaderFilter(IFilterForm $formConnector): self
    {
        $this->headerFilter = new Table\Filter($formConnector);
        return $this;
    }

    public function addFooterFilter(IFilterForm $formConnector): self
    {
        $this->footerFilter = new Table\Filter($formConnector);
        return $this;
    }

    /**
     * @param array<string, string> $attributes
     * @return $this
     */
    public function setDefaultHeaderFilterFieldAttributes(array $attributes): self
    {
        $this->defaultHeaderFilterFieldAttributes = $attributes;
        return $this;
    }

    /**
     * @param array<string, string> $attributes
     * @return $this
     */
    public function setDefaultFooterFilterFieldAttributes(array $attributes): self
    {
        $this->defaultFooterFilterFieldAttributes = $attributes;
        return $this;
    }

    public function addPager(IOutput $pager): self
    {
        $this->pager = $pager;
        return $this;
    }

    /**
     * Basic order
     * @param string $columnName
     * @param string $order
     * @throws TableException
     * @return $this
     */
    public function addOrdering(string $columnName, string $order = Table\Order::ORDER_ASC): self
    {
        $this->checkOrder();
        $this->order->/** @scrutinizer ignore-call */addOrdering($columnName, $order);
        return $this;
    }

    /**
     * More important order when some is already set
     * @param string $columnName
     * @param string $order
     * @throws TableException
     * @return $this
     */
    public function addPrimaryOrdering(string $columnName, string $order = Table\Order::ORDER_ASC): self
    {
        $this->checkOrder();
        $this->order->/** @scrutinizer ignore-call */addPrependOrdering($columnName, $order);
        return $this;
    }

    /**
     * @throws TableException
     */
    protected function checkOrder(): void
    {
        if (empty($this->order)) {
            throw new TableException('Need to set order library first!');
        }
    }

    public function getPager(): ?IOutput
    {
        return $this->pager;
    }

    public function getOrder(): ?Table\Order
    {
        return $this->order;
    }

    public function getHeaderFilter(): ?Table\Filter
    {
        return $this->headerFilter;
    }

    public function getFooterFilter(): ?Table\Filter
    {
        return $this->footerFilter;
    }

    public function getFormName(): string
    {
        return $this->headerFilter ? $this->headerFilter->getFormName() : ( $this->footerFilter ? $this->footerFilter->getFormName() : '' );
    }

    public function setOutput(Table\AOutput $output): void
    {
        $this->output = $output;
    }

    public function getOutput(): ?Table\AOutput
    {
        return $this->output;
    }

    /**
     * Change data source
     * @param IIterableConnector $dataSetConnector
     * @return $this
     */
    public function addDataSetConnector(IIterableConnector $dataSetConnector): self
    {
        $this->dataSetConnector = $dataSetConnector;
        return $this;
    }

    public function getDataSetConnector(): ?IIterableConnector
    {
        return $this->dataSetConnector;
    }

    /**
     * Returns column to another update
     * @param int $position
     * @return IColumn|null
     */
    public function getColumn(int $position): ?IColumn
    {
        return $this->columns[$position] ?? null ;
    }

    /**
     * Return columns
     * @return Table\Internal\Row[]|Table[]
     */
    public function &getTableData(): array
    {
        return $this->tableData;
    }

    /**
     * @return IColumn[]
     */
    public function &getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Return classes used for styles
     * @return string[]
     */
    public function &getClasses(): array
    {
        return $this->classes;
    }

    public function getClassesInString(): string
    {
        if (!empty($this->classes)) {
            return implode(' ', $this->classes);
        } else {
            return '';
        }
    }

    /**
     * @param string  $headerText
     * @param IColumn $column
     * @param IField|null $headerFilterField
     * @param IField|null $footerFilterField
     * @throws TableException
     * @return $this
     */
    public function addOrderedColumn(string $headerText, IColumn $column, ?IField $headerFilterField = null, ?IField $footerFilterField = null): self
    {
        if ($column->canOrder()) {
            $this->checkOrder();
            $this->order->/** @scrutinizer ignore-call */addColumn($column);
        }

        $this->addColumn($headerText, $column, $headerFilterField, $footerFilterField);

        return $this;
    }

    /**
     * @param string $headerText
     * @param IColumn $column
     * @param IField|null $headerFilterField
     * @param IField|null $footerFilterField
     * @throws TableException
     * @return $this
     */
    public function addColumn(string $headerText, IColumn $column, ?IField $headerFilterField = null, ?IField $footerFilterField = null): self
    {
        $column->setHeaderText($headerText);

        if (isset($headerFilterField)) {
            $headerFilterField->setAttributes($this->defaultHeaderFilterFieldAttributes);
            $column->setHeaderFiltering($headerFilterField);
        }

        if ($column->hasHeaderFilterField() && $this->headerFilter) {
            $this->headerFilter->addHeaderColumn($column);
        }

        if (isset($footerFilterField)) {
            $footerFilterField->setAttributes($this->defaultFooterFilterFieldAttributes);
            $column->setFooterFiltering($footerFilterField);
        }

        if ($column->hasFooterFilterField() && $this->footerFilter) {
            $this->footerFilter->addFooterColumn($column);
        }

        $this->columns[] = $column;

        return $this;
    }

    /**
     * Add Css class to the table
     * @param string $class
     */
    public function addClass(string $class): void
    {
        $this->classes[] = $class;
    }

    /**
     * Remover Css class from the table
     * @param string $class
     */
    public function removeClass($class): void
    {
        if (($key = array_search($class, $this->classes)) !== false) {
            unset($this->classes[$key]);
        }
    }

    /**
     * Render complete table - just helper method
     * @throws ConnectException
     * @throws TableException
     * @return string
     */
    public function render(): string
    {
        $this->translateData();
        if (!$this->output) {
            throw new TableException('Need to set output first!');
        }
        return $this->output->render();
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

        $this->dataSetConnector->/** @scrutinizer ignore-call */fetchData();

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
        }
    }

    /**
     * @throws ConnectException
     * @return $this
     */
    protected function applyFilter(): self
    {
        if (empty($this->headerFilter)) {
            return $this;
        }

        $this->headerFilter->process();

        foreach ($this->columns as $column) {
            if ($this->headerFilter->hasValue($column)) {

                $filterField = $column->getHeaderFilterField();
                if ($filterField) {
                    $filterField->setDataSourceConnector(/** @scrutinizer ignore-type */$this->dataSetConnector);
                    $this->dataSetConnector->/** @scrutinizer ignore-call */setFiltering(
                        $column->getSourceName(),
                        $filterField->getFilterAction(),
                        $this->headerFilter->getValue($column)
                    );
                }
            }
        }
        return $this;
    }

    /**
     * @throws ConnectException
     * @return $this
     */
    protected function applyOrder(): self
    {
        if (empty($this->order)) {
            return $this;
        }

        $this->order->process();
        foreach ($this->order->getOrdering() as $attributes) {
            /** @var Table\Internal\Attributes $attributes */
            $this->dataSetConnector->/** @scrutinizer ignore-call */setOrdering($attributes->getColumnName(), $attributes->getProperty());
        }
        return $this;
    }

    /**
     * @throws ConnectException
     * @return $this
     */
    protected function applyPager(): self
    {
        if (empty($this->pager)) {
            return $this;
        }

        if (empty($this->pager->getPager()->getMaxResults())) {
            $this->pager->getPager()->setMaxResults($this->dataSetConnector->/** @scrutinizer ignore-call */getTotalCount());
        }
        $this->dataSetConnector->/** @scrutinizer ignore-call */setPagination(
            $this->pager->getPager()->getOffset(),
            $this->pager->getPager()->getLimit()
        );
        return $this;
    }

    public function rowCount(): int
    {
        return count($this->tableData);
    }

    public function colCount(): int
    {
        return count($this->columns);
    }

    public function showPagerOnHead(): bool
    {
        return $this->showPagerOnHead;
    }

    public function showPagerOnFoot(): bool
    {
        return $this->showPagerOnFoot;
    }
}
