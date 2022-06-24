<?php

namespace kalanis\kw_table\core\Table\Rows;


use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class ClassRow
 * @package kalanis\kw_table\core\Table\Rows
 * The input is CSS class
 */
class ClassRow extends ARow
{
    /**
     * @param string $styleClass
     * @param string|IRule $rule
     * @param string $cell
     */
    public function __construct(string $styleClass, $rule, $cell)
    {
        $this->setFunctionName('class');
        $this->setFunctionArgs([$styleClass, $rule, $cell]);
    }
}
