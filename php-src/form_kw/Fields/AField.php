<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_forms\Form;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\TableException;


/**
 * Class AField
 * @package kalanis\kw_table\form_kw\Fields
 */
abstract class AField implements IField
{
    protected ?Form $form = null;
    protected string $alias = '';
    /** @var array<string, string> */
    protected array $attributes = [];
    protected ?IIterableConnector $connector = null;

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

    /**
     * @throws TableException
     * @return Form
     */
    public function getFormInstance(): Form
    {
        if (empty($this->form)) {
            throw new TableException('Set the form first!');
        }
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

    /**
     * @throws TableException
     * @return IIterableConnector
     */
    public function getDataSourceConnectorInstance(): IIterableConnector
    {
        if (empty($this->connector)) {
            throw new TableException('Set the datasource connector first!');
        }
        return $this->connector;
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
