<?php

namespace kalanis\kw_table\Table\Rules;


use kalanis\kw_table\Interfaces\Table\IRule;


/**
 * Class Exact
 * @package kalanis\kw_table\Table\Rules
 * Check if content is exact to...
 */
class Exact extends ARule implements IRule
{
    public function validate($value): bool
    {
        return ($this->base == $value);
    }
}
