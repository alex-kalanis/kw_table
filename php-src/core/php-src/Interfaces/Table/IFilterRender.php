<?php

namespace kalanis\kw_table\core\Interfaces\Table;


/**
 * Interface IFilterRender
 * @package kalanis\kw_table\core\Interfaces\Table
 * Special fields - not in filter form, render what they provide
 */
interface IFilterRender
{
    public function renderContent(): string;
}
