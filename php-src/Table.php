<?php

namespace kalanis\kw_table;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_paging\Interfaces\IOutput;
use kalanis\kw_table\Interfaces\Connector\IConnector;
use kalanis\kw_table\Interfaces\Connector\IField;
use kalanis\kw_table\Interfaces\Connector\IForm;
use kalanis\kw_table\Interfaces\Table\IColumn;
use kalanis\kw_table\Interfaces\Table\IRule;
use kalanis\kw_table\Table\Output\AOutput;


/**
 * Class Table
 * @package kalanis\kw_table
 * Main table render
 */
class Table
{
    const PAGER_LIMIT_DEFAULT = 30;

    /** @var int */
    protected $colCount = 0;

    /** @var IConnector|null */
    protected $dataSource = null;

    /** @var IColumn[] */
    protected $columns = [];

    /** @var Table\Rows\ARow[] */
    protected $callRows = [];

    /** @var string[] */
    protected $classes = ['table', 'table-bordered', 'table-striped', 'table-hover', 'table-condensed', 'bootstrap-datatable', 'listtable'];

    /** @var IOutput|null */
    protected $outputPager = null;

    /** @var Table\Sorter|null */
    protected $sorter = null;

    /** @var Table\Filter|null */
    protected $headerFilter = null;

    /** @var Table\Filter|null */
    protected $footerFilter = null;

    /** @var Table\Output\AOutput */
    protected $output = null;

    /** @var Table\Internal\Row[]|Table[] */
    protected $tableData = [];

    /** @var string[] */
    protected $defaultHeaderFilterFieldAttributes = [];

    /** @var array  */
    protected $defaultFooterFilterFieldAttributes = [];

    /** @var bool */
    protected $showPagerOnHead = false;

    /** @var bool */
    protected $showPagerOnFoot = true;

    /**
     * @param IConnector|null $source
     * @throws MapperException
     */
    public function __construct(IConnector $source = null)
    {
        $this->output = new Table\Output\HtmlTemplates($this);

        if (!is_null($source)) {
            $this->addDataSource($source);
        }
    }

    /**
     * Add external function to row for processing data
     * @param string $function
     * @param string[] $arguments
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
    public function rowClass(string $class, IRule $rule, $cell)
    {
        $this->callRows[] = new Table\Rows\ClassRow($class, $rule, $cell);
    }

    public function addSorter(Table\Sorter $sorter): self
    {
        $this->sorter = $sorter;
        return $this;
    }

    public function addHeaderFilter(IForm $formConnector): self
    {
        $this->headerFilter = new Table\Filter($formConnector);
        return $this;
    }

    public function addFooterFilter(IForm $formConnector): self
    {
        $this->footerFilter = new Table\Filter($formConnector);
        return $this;
    }

    public function setDefaultHeaderFilterFieldAttributes(array $attributes): self
    {
        $this->defaultHeaderFilterFieldAttributes = $attributes;
        return $this;
    }

    public function setDefaultFooterFilterFieldAttributes(array $attributes): self
    {
        $this->defaultFooterFilterFieldAttributes = $attributes;
        return $this;
    }

    public function addPager(IOutput $pager): self
    {
        $this->outputPager = $pager;
        return $this;
    }

    /**
     * @param string $columnName
     * @param string $order
     * @return $this
     * @throws MapperException
     */
    public function setDefaultSorting(string $columnName, string $order = Table\Sorter::ORDER_ASC): self
    {
        if (!isset($this->sorter)) {
            throw new MapperException('Need to ser sorter first!');
        }
        $this->sorter->addPrimaryOrdering($columnName, $order);

        return $this;
    }

    public function addOrdering(string $columnName, string $order = Table\Sorter::ORDER_ASC): self
    {
        $this->sorter->addOrdering($columnName, $order);
        return $this;
    }

    public function addPrimaryOrdering(string $columnName, string $order = Table\Sorter::ORDER_ASC): self
    {
        $this->sorter->addPrimaryOrdering($columnName, $order);
        return $this;
    }

    public function getOutputPager(): ?IOutput
    {
        return $this->outputPager;
    }

    public function getSorter(): ?Table\Sorter
    {
        return $this->sorter;
    }

    public function getHeaderFilter(): ?Table\Filter
    {
        return $this->headerFilter;
    }

    public function getFooterFilter(): ?Table\Filter
    {
        return $this->footerFilter;
    }

    public function setOutput(AOutput $output)
    {
        $this->output = $output;
    }

    public function getOutput(): AOutput
    {
        return $this->output;
    }

    /**
     * Change data source
     * @param IConnector $source
     * @return $this
     * @throws MapperException
     */
    public function addDataSource(IConnector $source): self
    {
        $this->dataSource = $source;

        if (isset($this->headerFilter)) {
            $this->applyFilter();
        }

        if (isset($this->sorter)) {
            $this->applySorter();
        }

        if (isset($this->outputPager)) {
            $this->applyPager();
        }

        $this->dataSource->fetchData();
        return $this;
    }

    public function getDataSource(): ?IConnector
    {
        return $this->dataSource;
    }

    /**
     * @return $this
     * @throws MapperException
     */
    public function applyFilter(): self
    {
        $this->headerFilter->fetch();

        foreach ($this->columns as $column) {
            if ($this->headerFilter->isValue($column)) {

                $filterField = $column->getHeaderFilterField();
                if ($filterField) {
                    $filterField->setDataSource($this->dataSource);
                    $this->dataSource->setFiltering($column->getSourceName(), $this->headerFilter->getValue($column), $filterField->getFilterType());
                }
            }
        }
        return $this;
    }

    /**
     * @return $this
     * @throws MapperException
     */
    public function applySorter(): self
    {
        $this->sorter->fetch();
        $orderings = $this->sorter->getOrderings();
        if (!empty($orderings)) {
            foreach ($orderings AS $ordering) {
                $this->dataSource->setSorting($ordering[0], $ordering[1]);
            }
        }
        return $this;
    }

    /**
     * @return $this
     * @throws MapperException
     */
    public function applyPager(): self
    {
        if (null === $this->outputPager->getPager()->getMaxResults()) {
            $this->outputPager->getPager()->setMaxResults($this->dataSource->getTotalCount());
        }
        $this->dataSource->setPagination($this->outputPager->getPager()->getOffset(), $this->outputPager->getPager()->getLimit());
        return $this;
    }

    /**
     * Returns column to another update
     * @param string|int $alias
     * @return IColumn
     */
    public function &getColumn($alias)
    {
        return $this->columns[$alias];
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
            return implode(" ", $this->classes);
        } else {
            return '';
        }
    }

    /**
     * @param string $headerText
     * @param IColumn $column
     * @param IField|null $headerFilterField
     * @param IField|null $footerFilterField
     * @return $this
     */
    public function addColumn(string $headerText, IColumn $column, ?IField $headerFilterField = null, ?IField $footerFilterField = null): self
    {
        $column->setHeaderText($headerText);

        if (isset($headerFilterField)) {
            $headerFilterField->setAttributes($this->defaultHeaderFilterFieldAttributes);
            $column->setHeaderFiltering($headerFilterField);
        }

        if ($column->hasHeaderFilterField()) {
            $this->headerFilter->addHeaderColumn($column);
        }

        if (isset($footerFilterField)) {
            $footerFilterField->setAttributes($this->defaultFooterFilterFieldAttributes);
            $column->setFooterFiltering($footerFilterField);
        }

        if ($column->hasFooterFilterField()) {
            $this->footerFilter->addFooterColumn($column);
        }

        $alias = $this->colCount;
        $this->colCount++;
        $this->columns[$alias] = $column;

        return $this;
    }

    /**
     * @param string  $headerText
     * @param IColumn $column
     * @param IField|null $headerFilterField
     * @param IField|null $footerFilterField
     * @return $this
     * @throws MapperException
     */
    public function addSortedColumn(string $headerText, IColumn $column, ?IField $headerFilterField = null, ?IField $footerFilterField = null): self
    {
        if ($column->isSortable()) {
            if (!isset($this->sorter)) {
                throw new MapperException('Need to set sorter first!!!');
            }
            $this->sorter->addColumn($column);
        }

        $this->addColumn($headerText, $column, $headerFilterField, $footerFilterField);

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
        }
    }

    /**
     * Render complete table
     * @return string
     * @throws TableException
     */
    public function render(): string
    {
        $this->translateData();
        return $this->output->render();
    }

    public function rowCount(): int
    {
        return count($this->tableData);
    }

    public function colCount(): int
    {
        return $this->colCount;
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
