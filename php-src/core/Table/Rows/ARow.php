<?php

namespace kalanis\kw_table\core\Table\Rows;


/**
 * Class ARow
 * @package kalanis\kw_table\core\Table\Rows
 * Abstract class what can be added into the row
 */
abstract class ARow
{
    /** @var callable|string|array<string|object> */
    protected $functionName = '';
    /** @var array<int, mixed> */
    protected $functionArgs = [];

    /**
     * @param callable|string|array<string|object> $functionName
     * @return $this
     */
    public function setFunctionName($functionName)
    {
        $this->functionName = $functionName;
        return $this;
    }

    /**
     * @param array<int, mixed> $functionArgs
     * @return $this
     */
    public function setFunctionArgs(array $functionArgs)
    {
        $this->functionArgs = $functionArgs;
        return $this;
    }

    /**
     * @return callable|string|array<string|object>
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }

    /**
     * @return array<int, mixed>
     */
    public function getFunctionArgs()
    {
        return $this->functionArgs;
    }
}
