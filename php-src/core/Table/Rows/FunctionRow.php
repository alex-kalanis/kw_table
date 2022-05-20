<?php

namespace kalanis\kw_table\core\Table\Rows;


/**
 * Class FunctionRow
 * @package kalanis\kw_table\core\Table\Rows
 * The input is function call
 */
class FunctionRow extends ARow
{
    /**
     * @param callable $funcName
     * @param string[] $funcArgs
     */
    public function __construct($funcName, array $funcArgs)
    {
        $this->setFunctionName($funcName);
        $this->setFunctionArgs($funcArgs);
    }
}
