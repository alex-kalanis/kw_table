<?php

namespace kalanis\kw_table\form_nette_bootstrap\Fields;


use kalanis\kw_table\form_nette\Fields\Range;


\kalanis\kw_table\form_nette_bootstrap\Controls\BootstrapRange::register();


/**
 * Class BootstrapRange
 * @package kalanis\kw_table_form_nette_bootstrap\Fields
 */
class BootstrapRange extends Range
{
    public function add(): void
    {
        $this->form->addBootstrapRange($this->alias, null, null, $this->attributes);
    }
}
