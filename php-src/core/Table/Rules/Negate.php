<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class Negate
 * @package kalanis\kw_table\core\Table\Rules
 * This rule negate contained one
 */
class Negate implements IRule
{
    /** @var IRule */
    protected $rule = null;

    public function __construct(IRule $rule)
    {
        $this->rule = $rule;
    }

    public function validate($value): bool
    {
        return !$this->rule->validate($value);
    }
}
