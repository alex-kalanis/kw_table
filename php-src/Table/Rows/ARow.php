<?php

namespace kalanis\kw_table\Table\Rows;


/**
 * Class ARow
 * @package kalanis\kw_table\Table\Rows
 * Abstract class what can be added into the row
 */
class ARow
{
    protected $functionName = '';
    protected $functionArgs = [];

    public function setFunctionName(callable $functionName)
    {
        $this->functionName = $functionName;
        return $this;
    }

    public function setFunctionArgs(array $functionArgs)
    {
        $this->functionArgs = $functionArgs;
        return $this;
    }

    public function getFunctionName()
    {
        return $this->functionName;
    }

    public function getFunctionArgs()
    {
        return $this->functionArgs;
    }
}
