<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


/**
 * Class TextContains
 * @package kalanis\kw_table\form_kw\Fields
 */
class TextContains extends AField
{
    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_CONTAINS;
    }

    public function add(): void
    {
        $this->form->/** @scrutinizer ignore-call */addText($this->alias, '', null, $this->attributes);
    }
}
