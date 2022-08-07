<?php

namespace kalanis\kw_table\form_nette_lte\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_table\form_nette\Fields;


/**
 * Class DateTimePicker
 * @package kalanis\kw_table\form_nette_lte\Controls
 */
class DateTimePicker extends Fields\AField
{
    /** @var \DateTime|null */
    protected $startTime;
    /** @var \DateTime|null */
    protected $endTime;
    /** @var string|null */
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
