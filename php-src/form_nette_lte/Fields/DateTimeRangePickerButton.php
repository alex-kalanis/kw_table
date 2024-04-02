<?php

namespace kalanis\kw_table_form_nette_lte\Fields;


use DateTime as dt;
use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_table\form_nette\Fields\AField;


\kalanis\kw_table\form_nette_lte\Controls\DateTimeRangeButton::register();


/**
 * Class DateTimeRangePickerButton
 * @package kalanis\kw_table\form_nette_lte\Fields
 */
class DateTimeRangePickerButton extends AField
{
    protected ?string $searchFormat = null;
    protected ?dt $startTime;
    protected ?dt $endTime;

    public function __construct(?string $searchFormat = null, ?dt $startTime = null, ?dt $endTime = null, array $attributes = [])
    {
        $this->searchFormat = $searchFormat;
        $this->startTime = $startTime;
        $this->endTime = $endTime;

        parent::__construct($attributes);
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_RANGE;
    }

    public function add(): void
    {
        $this->form->addDateTimeRangeButton($this->alias, null, null, $this->searchFormat, $this->startTime, $this->endTime);
    }
}
