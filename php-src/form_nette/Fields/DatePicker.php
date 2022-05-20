<?php

namespace kalanis\kw_table\form_nette\Fields;


/**
 * Class DatePicker
 * @package kalanis\kw_table\form_nette\Fields
 */
class DatePicker extends AField
{
    public function getFilterAction(): string
    {
        return 'dateInTimestamp';
    }

    public function add(): void
    {
        $this->form->addTbDatePicker($this->alias)->setAttribute('class', 'listingDatePicker');
    }
}
