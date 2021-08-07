<?php

namespace kalanis\kw_table\Connector\Form\KwField;


use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Interfaces\Connector\IFilterType;


/**
 * Class MultiSelect
 * @package kalanis\kw_table\Connector\Form\KwField
 * Field for selecting more than one entry, usually everything
 */
class MultiSelect extends AField
{
    /** @var string */
    protected $value = '0';

    /**
     * @param string $value
     * @param array $attributes
     */
    public function __construct($value = '0', array $attributes = [])
    {
        $this->setValue($value);
        parent::__construct($attributes);
    }

    public function getFilterType(): IFilterType
    {
        return Factory::getInstance()->getFilter($this->dataSource->getFilterType(), 'exact');
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function add(): void
    {
        $this->form->addCheckbox($this->alias, '', null, $this->value, $this->attributes);
    }
}
