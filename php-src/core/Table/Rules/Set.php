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

    public function addRule(IRule $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * @param bool $all
     * @return $this
     */
    public function allMustPass($all = true): self
    {
        $this->all = boolval($all);
        return $this;
    }

    /**
     * Check each item
     * @param string $value
     * @throws TableException
     * @return bool
     */
    public function validate($value): bool
    {
        $trueCount = 0;

        foreach ($this->rules as $rule) {
            if ($rule->validate($value)) {
                $trueCount++;
            }
        }

        if ((false === $this->all) && (0 < $trueCount)) {
            return true;
        }

        if ($trueCount == count($this->rules)) {
            return true;
        }

        return false;
    }
}
