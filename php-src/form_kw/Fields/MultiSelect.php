<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


/**
 * Class MultiSelect
 * @package kalanis\kw_table\form_kw\Fields
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

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
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
