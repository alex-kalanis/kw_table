<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


/**
 * Class NumFrom
 * @package kalanis\kw_table\form_kw\Fields
 */
class NumFrom extends AField
{
    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_FROM;
    }

    public function add(): void
    {
        $this->form->/** @scrutinizer ignore-call */addText($this->alias, '', null, $this->attributes);
    }
}
