<?php

namespace kalanis\kw_table\Table\Rules;


use kalanis\kw_table\Interfaces\Table\IRule;
use kalanis\kw_table\TableException;


/**
 * Class REval
 * @package kalanis\kw_table\Table\Rules
 * Check content with validation against predefined value
 */
class REval extends ARule implements IRule
{
    public function validate($value): bool
    {
        if (preg_match('/(<|>|<=|>=|=|==)\s?(.*)/i', $this->base, $matches)) {
            switch ($matches[1]) {
                case "<":
                    return $value < $matches[2];
                    break;
                case ">":
                    return $value > $matches[2];
                    break;
                case "<=":
                    return $value <= $matches[2];
                    break;
                case ">=":
                    return $value >= $matches[2];
                    break;
                case "=":
                    return $value == $matches[2];
                    break;
                case "==":
                    return $value === $matches[2];
                    break;
                default:
                    throw new TableException('Unrecognized expression');
                    break;
            }
        } else {
            throw new TableException('Unrecognized expression');
        }
    }
}
