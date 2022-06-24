<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_table\core\Interfaces\Table\IRule;
use kalanis\kw_table\core\TableException;


/**
 * Class REval
 * @package kalanis\kw_table\core\Table\Rules
 * Check content with validation against predefined value
 */
class REval extends ARule implements IRule
{
    public function validate($value): bool
    {
        if (preg_match('/([^\s]+)\s(.*)/i', strval($this->base), $matches)) {
            return $this->compare($value, $matches[1], $matches[2]);
        } elseif(preg_match('/(<|>|<=|>=|=|==)(.*)/i', strval($this->base), $matches)) {
            return $this->compare($value, $matches[1], $matches[2]);
        } else {
            throw new TableException('Unrecognized expression pattern');
        }
    }

    /**
     * @param mixed $value
     * @param string $expression
     * @param string $against
     * @throws TableException
     * @return bool
     */
    protected function compare($value, $expression, $against): bool
    {
        switch ($expression) {
            case '<':
                return $value < $against;
            case '>':
                return $value > $against;
            case '<=':
                return $value <= $against;
            case '>=':
                return $value >= $against;
            case '=':
                return $value == $against;
            case '!=':
                return $value != $against;
            case '==':
                return $value === $against;
            default:
                throw new TableException('Unrecognized expression sign');
        }
    }
}
