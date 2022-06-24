<?php

namespace kalanis\kw_table\core\Connector;


use kalanis\kw_table\core\Interfaces\Form;


/**
 * Class ArrayForm
 * @package kalanis\kw_table\Connector
 * Pass params in simple array
 */
class ArrayFilterForm implements Form\IFilterForm
{
    /** @var array<string, string|int|float|bool|null> */
    protected $formData = [];

    /**
     * @param array<string, string|int|float|bool|null> $filterParams
     */
    public function __construct($filterParams = [])
    {
        $this->formData = $filterParams;
    }

    public function addField(Form\IField $field): void
    {
    }

    public function setValue(string $alias, $value): void
    {
        $this->formData[$alias] = $value;
    }

    public function getValues(): array
    {
        return $this->formData;
    }

    public function getValue(string $alias)
    {
        if (empty($this->formData[$alias])) {
            return null;
        }
        return $this->formData[$alias];
    }

    public function getFormName(): string
    {
        return '';
    }

    public function renderStart(): string
    {
        return '';
    }

    public function renderEnd(): string
    {
        return '';
    }

    public function renderField(string $alias): string
    {
        return '';
    }
}
