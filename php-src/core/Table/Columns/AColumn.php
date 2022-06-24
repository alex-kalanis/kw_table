<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\Interfaces\Table\IColumn;
use kalanis\kw_table\core\Table\AStyle;
use kalanis\kw_table\core\Table\TSourceName;


/**
 * Class AColumn
 * @package kalanis\kw_table\Table\Columns
 */
abstract class AColumn extends AStyle implements IColumn
{
    use TSourceName;
    use TWrappers;

    /** @var string|int */
    protected $sourceName = '';
    /** @var string */
    protected $filterName = '';
    /** @var bool */
    protected $sortable = false;
    /** @var IField|null */
    protected $headerFilterField = null;
    /** @var IField|null */
    protected $footerFilterField = null;
    /** @var string|null */
    protected $headerText = '';

    /**
     * @param string $text
     * @return $this
     */
    public function setHeaderText(?string $text)
    {
        $this->headerText = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeaderText(): string
    {
        return is_null($this->headerText) ? $this->getFilterName() : $this->headerText ;
    }

    public function translate(IRow $source): string
    {
        return $this->formatData(strval($this->getValue($source)));
    }

    public function getFilterName(): string
    {
        return empty($this->filterName) ? strval($this->getSourceName()) : $this->filterName ;
    }

    /**
     * Returns value from row
     * @param IRow $source
     * @throws ConnectException
     * @return string|int|float|bool|null
     */
    public function getValue(IRow $source)
    {
        return $this->value($source, $this->getSourceName());
    }

    /**
     * @param IRow $source
     * @param string|int $overrideProperty
     * @throws ConnectException
     * @return string|int|float|bool|null
     */
    public function getOverrideValue(IRow $source, $overrideProperty)
    {
        return $this->value($source, $overrideProperty);
    }

    /**
     * @param IRow $source
     * @param string|int $property
     * @throws ConnectException
     * @return string|int|float|bool
     */
    protected function value(IRow $source, $property)
    {
        return $source->getValue($property);
    }

    public function canOrder(): bool
    {
        return true;
    }

    public function hasHeaderFilterField(): bool
    {
        return $this->headerFilterField && $this->headerFilterField instanceof IField;
    }

    public function hasFooterFilterField(): bool
    {
        return $this->footerFilterField && $this->footerFilterField instanceof IField;
    }

    public function setHeaderFiltering(?IField $field): self
    {
        $this->headerFilterField = $field;
        return $this;
    }

    public function setFooterFiltering(IField $field)
    {
        $this->footerFilterField = $field;
        return $this;
    }

    public function getHeaderFilterField(): ?IField
    {
        return $this->headerFilterField;
    }

    public function getFooterFilterField(): ?IField
    {
        return $this->footerFilterField;
    }
}
