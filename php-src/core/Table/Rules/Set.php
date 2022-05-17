<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_table\core\Interfaces\Table\IRule;
use kalanis\kw_table\core\TableException;


/**
 * Class Set
 * @package kalanis\kw_table\core\Table\Rules
 * Use multiple rules for rendering table
 */
class Set implements IRule
{
    /** @var IRule[] */
    protected $rules = [];
    /** @var bool */
    protected $all = true;

    public function addRule(IRule $rule)
    {
        $this->rules[] = $rule;
    }

    public function allMustPass($all = true)
    {
        $this->all = (bool)$all;
    }

    /**
     * Check each item
     * @param string $value
     * @return bool
     * @throws TableException
     */
    public function validate($value): bool
    {
        $trueCount = 0;

        foreach ($this->rules as $rule) {
            if ($rule->validate($value)) {
                $trueCount++;
            }
        }

        if (false == $this->all && 0 < $trueCount) {
            return true;
        }

        if ($trueCount == count($this->rules)) {
            return true;
        }

        return false;
    }
}
