<?php

namespace kalanis\kw_table\form_nette\Fields;


use kalanis\kw_connect\core\Interfaces\IConnector;
use kalanis\kw_connect\core\Interfaces\IFilterType;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\form_nette\NetteForm;
use Nette\Application\UI\Form as BaseForm;


/**
 * Class AField
 * @package kalanis\kw_table\form_nette\Fields
 */
abstract class AField implements IField
{
    const ATTR_SIZE = 'size';

    /** @var BaseForm|NetteForm */
    protected $form;
    /** @var string */
    protected $alias;
    /** @var array */
    protected $attributes = [];
    /** @var IConnector|null */
    protected $dataSource;

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param BaseForm $form
     * @return $this
     */
    public function setForm(BaseForm $form)
    {
        $this->form = $form;
        return $this;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function setDataSourceConnector(IConnector $dataSource): void
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addAttribute(string $name, string $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes + $this->attributes;
    }

    public function prepareAlias()
    {
        $this->alias = str_replace('.', '_', $this->alias);
    }

    public function processAttributes()
    {
        foreach ($this->attributes AS $name => $value) {
            $this->form[$this->alias]->setAttribute($name, $value);
        }
    }

    public function getFilterType(): IFilterType
    {
        return $this->dataSource->getFilterFactory()->getFilter($this->getFilterAction());
    }

    abstract public function getFilterAction(): string;
}
