<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class REmpty
 * @package kalanis\kw_table\core\Table\Rules
 * Check if content is considered empty
 */
class REmpty extends ARule implements IRule
{
    public function validate($value): bool
    {
        return empty($value);
    }
}
