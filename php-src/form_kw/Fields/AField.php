<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_forms\Form;
use kalanis\kw_table\core\Interfaces\Form\IField;


/**
 * Class AField
 * @package kalanis\kw_table\form_kw\Fields
 */
abstract class AField implements IField
{
    /** @var Form|null */
    protected $form = null;
    /** @var string */
    protected $alias = '';
    /** @var array<string, string> */
    protected $attributes = [];
    /** @var IIterableConnector|null */
    protected $connector = null;

    /**
     * @param array<string, string> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
        $this->connector = $dataSource;
    }

    public function addAttribute(string $name, string $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @param array<string, string> $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes + $this->attributes;
    }
}
