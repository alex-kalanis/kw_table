<?php

namespace kalanis\kw_table\core\Table\Rows;


/**
 * Class ClassRow
 * @package kalanis\kw_table\core\Table\Rows
 * The input is CSS class
 */
class ClassRow extends ARow
{
    const ARG_CLASS = 0;
    const ARG_RULE = 1;
    const ARG_CELL = 2;

    public function __construct(string $styleClass, $rule, $cell)
    {
        $this->setFunctionName('class');
        $this->setFunctionArgs([
            static::ARG_CLASS => $styleClass,
            static::ARG_RULE => $rule,
            static::ARG_CELL => $cell
        ]);
    }
}
