<?php

namespace kalanis\kw_table\core\Interfaces\Table;


use kalanis\kw_table\core\TableException;


/**
 * Interface IRule
 * @package kalanis\kw_table\core\Interfaces\Table
 * Rules over entries, usually for applying different styles for different outputs
 */
interface IRule
{
    /**
     * @param string $value
     * @throws TableException
     * @return bool
     * @see \kalanis\kw_table\core\Table\AStyle::isStyleApplied
     */
    public function validate($value): bool;
}
