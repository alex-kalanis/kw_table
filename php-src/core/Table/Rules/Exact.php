<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class Exact
 * @package kalanis\kw_table\core\Table\Rules
 * Check if content is exact to...
 */
class Exact extends ARule implements IRule
{
    public function validate($value): bool
    {
        return ($this->base == $value);
    }
}
