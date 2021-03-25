<?php

namespace kalanis\kw_table\Table;


use kalanis\kw_table\Interfaces\Connector\IFilterType;
use kalanis\kw_table\Interfaces\Table\IColumn;
use kalanis\kw_table\Interfaces\Connector\IForm;
use kalanis\kw_table\Interfaces\Table\IFilterRender;
use kalanis\kw_table\TableException;


/**
 * Class Filter
 * @package kalanis\kw_table\Table
 */
class Filter
{
    /** @var IForm */
    protected $formConnector;
    /** @var string[]|int[] */
    protected $columnsValues = [];
    /** @var IColumn[] */
    protected $headerColumns = [];
    /** @var IColumn[] */
    protected $footerColumns = [];
    /** @var string */
    protected $footerPrefix = 'foot_';

    public function __construct(IForm $connector)
    {
        $this->formConnector = $connector;
    }

    public function isValue(IColumn $column): bool
    {
        return isset($this->columnsValues[$column->getSourceName()]) && $this->columnsValues[$column->getSourceName()] !== IFilterType::EMPTY_FILTER;
    }

    /**
     * @param IColumn $column
     * @return string|int
     */
    public function getValue(IColumn $column)
    {
        if (!$this->isValue($column)) {
            return IFilterType::EMPTY_FILTER;
        }

        return $this->columnsValues[$column->getSourceName()];
    }

    public function getFormName(): string
    {
        return $this->formConnector->getFormName();
    }

    public function addHeaderColumn(IColumn $column): self
    {
        $filterField = $column->getHeaderFilterField();
        $filterField->setAlias($column->getSourceName());
        $this->formConnector->addField($filterField);

        $name = $column->getSourceName();
        $this->headerColumns[$name] = $column;
        $this->columnsValues[$name] = '';

        return $this;
    }

    public function addFooterColumn(IColumn $column): self
    {
        $name = $this->footerPrefix . $column->getSourceName();

        $filterField = $column->getFooterFilterField();
        $filterField->setAlias($name);
        $this->formConnector->addField($filterField);

        $this->footerColumns[$name] = $column;
        $this->columnsValues[$name] = '';

        return $this;
    }

    public function renderStart(): string
    {
        return $this->formConnector->renderStart();
    }

    public function renderEnd(): string
    {
        return $this->formConnector->renderEnd();
    }

    /**
     * @param IColumn $column
     * @return string
     * @throws TableException
     */
    public function renderHeaderInput(IColumn $column): string
    {
        $name = $column->getSourceName();
        if (!array_key_exists($name, $this->headerColumns)) {
            throw new TableException('Column not filtered: ' . $name);
        }

        $field = $column->getHeaderFilterField();
        if ($field instanceof IFilterRender) { // not every time it's form
            return $field->renderContent();
        } else {
            return $this->formConnector->renderField($name);
        }
    }

    /**
     * @param IColumn $column
     * @return string
     * @throws TableException
     */
    public function renderFooterInput(IColumn $column): string
    {
        $name = $this->footerPrefix . $column->getSourceName();
        if (!array_key_exists($name, $this->footerColumns)) {
            throw new TableException('Column not filtered: ' . $name);
        }

        $field = $column->getFooterFilterField();
        if ($field instanceof IFilterRender) { // not every time it's form
            return $field->renderContent();
        } else {
            return $this->formConnector->renderField($name);
        }
    }

    public function fetch(): self
    {
        foreach ($this->columnsValues as $name => &$value) {
            $value = $this->formConnector->getValue($name);
        }

        return $this;
    }

    public function getConnector(): IForm
    {
        return $this->formConnector;
    }
}
