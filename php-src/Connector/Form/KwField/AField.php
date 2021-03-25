<?php

namespace kalanis\kw_table\Connector\Form\KwField;


use kalanis\kw_forms\Form;
use kalanis\kw_table\Interfaces\Connector\IConnector;
use kalanis\kw_table\Interfaces\Connector\IField;


/**
 * Class AField
 * @package kalanis\kw_table\Connector\Form\Field
 */
abstract class AField implements IField
{
    /** @var Form */
    protected $form = null;
    /** @var string */
    protected $alias = '';
    /** @var string[]|int[] */
    protected $attributes = [];
    /** @var IConnector */
    protected $dataSource = null;

    /**
     * @param string[] $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function setForm(Form $form): void
    {
        $this->form = $form;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function setDataSource(IConnector $dataSource): void
    {
        $this->dataSource = $dataSource;
    }

    public function addAttribute(string $name, string $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes + $this->attributes;
    }
}
