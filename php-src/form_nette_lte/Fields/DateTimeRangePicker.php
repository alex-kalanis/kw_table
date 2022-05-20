<?php

namespace kalanis\kw_table_form_nette_lte\Controls;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_table\form_nette\Fields;


\kalanis\kw_table\form_nette_lte\Controls\DateTimeRange::register();


/**
 * Class Listing\Connector\Form\Field\Nette\DateTimePicker
 * @package kalanis\kw_table\form_nette_lte\Controls
 */
class DateTimeRangePicker extends Fields\AField
{
    protected $startTime;
    protected $endTime;
    protected $searchFormat;

    public function __construct(?\DateTime $startTime = null, ?\DateTime $endTime = null, ?string $searchFormat = null, array $attributes = [])
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->searchFormat = $searchFormat;

        parent::__construct($attributes);
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_RANGE;
    }

    public function add(): void
    {
        $this->form->addAdminLteDateTimeRange($this->alias, null, null, $this->searchFormat, $this->startTime, $this->endTime);
    }
}