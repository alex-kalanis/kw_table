<?php

namespace kalanis\kw_table\core\Interfaces\Table;


/**
 * Interface IFilterMulti
 * @package kalanis\kw_table\core\Interfaces\Table
 * Filter multiple content
 */
interface IFilterMulti
{
    /**
     * @return array <string, string>
     */
    public function getPairs(): array;
}
