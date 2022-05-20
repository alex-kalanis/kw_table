<?php

namespace kalanis\kw_table\core\Table\Rows;


/**
 * Class ARow
 * @package kalanis\kw_table\core\Table\Rows
 * Abstract class what can be added into the row
 */
abstract class ARow
{
    protected $functionName = '';
    protected $functionArgs = [];

    /**
     * @param callable $functionName
     * @return $this
     */
    public function setFunctionName($functionName)
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
