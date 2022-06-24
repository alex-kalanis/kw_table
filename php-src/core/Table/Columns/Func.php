<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class Func
 * @package kalanis\kw_table\core\Table\Columns
 * Each row in Column will pass through external function
 */
class Func extends AColumn
{
    /** @var callable */
    protected $callback;
    /** @var array<int, float|int|string|bool|null> */
    protected $param;

    /**
     * @param string $sourceName
     * @param callable $callback
     * @param array<int, float|int|string|bool|null> $param+
     */
    public function __construct(string $sourceName, $callback, array $param = [])
    {
        $this->sourceName = $sourceName;
        $this->callback = $callback;
        $this->param = $param;
    }

    public function getValue(IRow $source)
    {
        $param = array_merge([parent::getValue($source)], $this->param);
        return call_user_func_array($this->callback, $param);
    }
}
