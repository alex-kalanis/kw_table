<?php

namespace kalanis\kw_table\form_nette\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


\kalanis\kw_table\form_nette\Controls\Range::register();


/**
 * Class Range
 * @package kalanis\kw_table\form_nette\Fields
 */
class Range extends AField
{
    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_RANGE;
    }

    public function add(): void
    {
        $this->form->addRange($this->alias, null, null, $this->attributes);
    }
}
