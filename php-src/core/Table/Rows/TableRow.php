<?php

namespace kalanis\kw_table\core\Table\Rows;


/**
 * Class TableRow
 * @package kalanis\kw_table\core\Table\Rows
 * Input is another table
 */
class TableRow extends ARow
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
