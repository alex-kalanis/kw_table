<?php

namespace kalanis\kw_table\Interfaces\Table;


use kalanis\kw_table\TableException;


/**
 * Interface IRule
 * @package kalanis\kw_table\Interfaces\Table
 * Rules over entries, usually for applying different styles for different outputs
 */
interface IRule
{
    /**
     * @param string $value
     * @return bool
     * @throws TableException
     * @see \kalanis\kw_table\Table\AStyle::isStyleApplied
     */
    public function validate(string $value): bool;
}
