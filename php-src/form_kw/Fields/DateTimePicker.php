<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


/**
 * Class DateTimePicker
 * @package kalanis\kw_table\form_kw\Fields
 */
class DateTimePicker extends AField
{
    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    public function add(): void
    {
        $this->form->/** @scrutinizer ignore-call */addDateTimePicker($this->alias, '', null, $this->attributes);
    }
}
