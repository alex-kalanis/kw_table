<?php

namespace kalanis\kw_table\Connector\Form\KwField;


use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Interfaces\Connector\IFilterType;


/**
 * Class DateRange
 * @package kalanis\kw_table\Connector\Form\Field
 */
class DateRange extends AField
{
    public function getFilterType(): IFilterType
    {
        return Factory::getInstance()->getFilter($this->dataSource->getFilterType(), 'range');
    }

    public function add(): void
    {
        $this->form->addDateRange($this->alias, '', null, $this->attributes);
    }
}
