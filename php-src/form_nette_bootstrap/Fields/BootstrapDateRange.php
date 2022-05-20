<?php

namespace kalanis\kw_table\form_nette_bootstrap\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_table\form_nette\Fields\AField;


\kalanis\kw_table\form_nette_bootstrap\Controls\BootstrapDateRange::register();


/**
 * Class BootstrapDateRange
 * @package kalanis\kw_table\form_nette_bootstrap\Fields
 */
class BootstrapDateRange extends AField
{
    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_RANGE;
    }

    public function add(): void
    {
        $this->form->addBootstrapDateRange($this->alias, null, null, $this->attributes);
    }
}
