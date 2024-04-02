<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;


/**
 * Class TextExact
 * @package kalanis\kw_table\form_kw\Fields
 */
class TextExact extends AField
{
    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    public function add(): void
    {
        $this->getFormInstance()->addText($this->alias, '', null, $this->attributes);
    }
}
