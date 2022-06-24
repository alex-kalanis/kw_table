<?php

namespace kalanis\kw_table\core\Table;


use kalanis\kw_connect\core\Interfaces\IFilterType;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\Interfaces\Form\IFilterForm;
use kalanis\kw_table\core\Interfaces\Table\IColumn;
use kalanis\kw_table\core\Interfaces\Table\IFilterMulti;
use kalanis\kw_table\core\Interfaces\Table\IFilterRender;
use kalanis\kw_table\core\TableException;


/**
 * Class Filter
 * @package kalanis\kw_table\core\Table
 */
class Filter
{
    /** @var IFilterForm */
    protected $formConnector;
    /** @var string[]|int[] */
    protected $columnsValues = [];
    /** @var IColumn[] */
    protected $headerColumns = [];
    /** @var IColumn[] */
    protected $footerColumns = [];
    /** @var string */
    protected $headerPrefix = '';
    /** @var string */
    protected $footerPrefix = 'foot_';

    public function __construct(IFilterForm $connector)
    {
        $this->formConnector = $connector;
    }

    public function hasValue(IColumn $column): bool
    {
        return isset($this->columnsValues[$column->getSourceName()]) && IFilterType::EMPTY_FILTER !== $this->columnsValues[$column->getSourceName()];
    }

    /**
     * @param IColumn $column
     * @return float|int|string|bool|null
     */
    public function getValue(IColumn $column)
    {
        if (!$this->hasValue($column)) {
            return IFilterType::EMPTY_FILTER;
        }
        return $this->columnsValues[$column->getSourceName()];
    }

    public function getFormName(): string
    {
        return $this->formConnector->getFormName();
    }

    /**
     * @param IColumn $column
     * @throws TableException
     * @return $this
     */
    public function addHeaderColumn(IColumn $column): self
    {
        $filterField = $column->getHeaderFilterField();
        if ($filterField) {
            $filterField->setAlias($this->headerPrefix . $column->getFilterName());
            $this->formConnector->addField($filterField);
            $this->headerColumns[$this->headerPrefix . $column->getSourceName()] = $column;
        }
        return $this;
    }

    /**
     * @param IColumn $column
     * @throws TableException
     * @return $this
     */
    public function addFooterColumn(IColumn $column): self
    {
        $filterField = $column->getFooterFilterField();
        if ($filterField) {
            $filterField->setAlias($this->footerPrefix . $column->getFilterName());
            $this->formConnector->addField($filterField);
            $this->footerColumns[$this->footerPrefix . $column->getSourceName()] = $column;
        }
        return $this;
    }

    /**
     * @throws RenderException
     * @return string
     */
    public function renderStart(): string
    {
        return $this->formConnector->renderStart();
    }

    /**
     * @throws RenderException
     * @return string
     */
    public function renderEnd(): string
    {
        return $this->formConnector->renderEnd();
    }

    /**
     * @param IColumn $column
     * @throws RenderException
     * @throws TableException
     * @return string
     */
    public function renderHeaderInput(IColumn $column): string
    {
        $name = $this->headerPrefix . $column->getSourceName();
        if (!array_key_exists($name, $this->headerColumns)) {
            throw new TableException('Column not filtered: ' . $name);
        }

        $field = $column->getHeaderFilterField();
        if ($field instanceof IFilterRender) { // not every time it's form
            return $field->renderContent();
        } else {
            return $this->formConnector->renderField($this->headerPrefix . $column->getFilterName());
        }
    }

    /**
     * @param IColumn $column
     * @throws RenderException
     * @throws TableException
     * @return string
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
            return $this->formConnector->renderField($this->footerPrefix . $column->getFilterName());
        }
    }

    public function process(): self
    {
        $formValues = $this->formConnector->getValues();
        $original = [];
        foreach ($this->headerColumns as &$column) {
            /** @var IColumn $column */
            $this->addValuesToArray(
                $original,
                $column->getSourceName(),
                $this->getValuesFromFilters(
                    $formValues,
                    $column->getFilterName(),
                    $column->getHeaderFilterField()
                )
            );
        }
        foreach ($this->footerColumns as &$column) {
            /** @var IColumn $column */
            $this->addValuesToArray(
                $original,
                $column->getSourceName(),
                $this->getValuesFromFilters(
                    $formValues,
                    $column->getFilterName(),
                    $column->getFooterFilterField()
                )
            );
        }
        $this->columnsValues = $original;
        return $this;
    }

    /**
     * @param array<string|int, float|int|string|bool|null> $formValues
     * @param string $filterName
     * @param IField|null $filterField
     * @return float|int|string|bool|array<int, array<int, float|int|string|bool|null>>|null
     */
    protected function getValuesFromFilters(array $formValues, string $filterName, ?IField $filterField)
    {
        if ($filterField instanceof IFilterMulti) {
            return $filterField->getPairs();
        } elseif (isset($formValues[$filterName])) {
            return $formValues[$filterName];
        } else {
            return null;
        }
    }

    /**
     * @param string[]|string[][] $original
     * @param string|int $sourceName
     * @param string|int|float|bool|array<int, array<int, string|int|float|bool|null>>|null $values
     * @todo: idea - probably make it array in full, then problems with detection will disappear
     */
    protected function addValuesToArray(array &$original, $sourceName, $values): void
    {
        if (empty($values)) {
            return;
        } elseif (!isset($original[$sourceName])) {
            // no target, flush it directly
            $original[$sourceName] = $values;
        } elseif (is_array($original[$sourceName])) {
            // target is array
            if (is_array($values)) {
                // source is array too
                $original[$sourceName] = array_merge($original[$sourceName], $values);
            } else {
                // source is primitive
                $original[$sourceName][] = $values;
            }
        } else {
            // target is primitive, came next entry -> make array
            $current = $original[$sourceName];
            $original[$sourceName] = [];
            $original[$sourceName][] = $current;
            // now target is array
            if (is_array($values)) {
                $original[$sourceName] = array_merge($original[$sourceName], $values);
            } else {
                $original[$sourceName][] = $values;
            }
        }
    }

    public function getConnector(): IFilterForm
    {
        return $this->formConnector;
    }
}
