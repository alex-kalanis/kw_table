<?php

namespace kalanis\kw_table\Connector\Form;


use kalanis\kw_table\Interfaces\Connector;



class ArrayForm implements Connector\IForm
{
    /** @var string[] */
    protected $formData = [];

    public function __construct($filterParams = [])
    {
        $this->formData = $filterParams;
    }

    public function addField(Connector\IField $field): void
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
