<?php

namespace kalanis\kw_table\Table\Rows;


/**
 * Class FunctionRow
 * @package kalanis\kw_table\Table\Rows
 * The input is function call
 */
class FunctionRow extends ARow
{
    public function __construct(callable $funcName, array $funcArgs)
    {
        $this->setFunctionName($funcName);
        $this->setFunctionArgs($funcArgs);
    }
}
