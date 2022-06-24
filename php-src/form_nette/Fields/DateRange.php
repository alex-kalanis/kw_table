<?php

namespace kalanis\kw_table\form_nette\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


\kalanis\kw_table\form_nette\Controls\DateRange::register();


/**
 * Class DateRange
 * @package kalanis\kw_table_form_nette\Fields
 */
class DateRange extends AField
{
    /** @var string */
    protected $inputFormat;
    /** @var string */
    protected $searchFormat;

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_RANGE;
    }

    public function add(): void
    {
        $this->form->addTbDateRange($this->alias, null, null, $this->attributes);
        if ($this->inputFormat) {
            $this->form[$this->alias]->setInputFormat($this->inputFormat);
        }
        if ($this->searchFormat) {
            $this->form[$this->alias]->setSearchFormat($this->searchFormat);
        }
    }

    public function setInputFormat($format)
    {
        $this->inputFormat = $format;
        return $this;
    }

    public function setSearchFormat($format)
    {
        $this->searchFormat = $format;
        return $this;
    }
}
