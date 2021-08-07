<?php

namespace kalanis\kw_table\Connector\Form\KwField;


use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Interfaces\Connector\IFilterType;


/**
 * Class DateTimePicker
 * @package kalanis\kw_table\Connector\Form\Field
 */
class DateTimePicker extends AField
{
    public function getFilterType(): IFilterType
    {
        return Factory::getInstance()->getFilter($this->dataSource->getFilterType(), 'exact');
    }

    public function add(): void
    {
        $this->form->addDateTimePicker($this->alias, '', null, $this->attributes);
    }
}
