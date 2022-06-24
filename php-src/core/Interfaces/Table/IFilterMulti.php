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
     * @return array<int, array<int, float|int|string|true>>
     * array of [action: string; current value: string]
     */
    public function getPairs(): array;
}
