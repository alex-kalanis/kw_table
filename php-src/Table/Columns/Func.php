<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Func
 * @package kalanis\kw_table\Table\Columns
 * Each row in Column will pass through external function
 */
class Func extends AColumn
{
    /** @var callable */
    protected $callback;
    /** @var array|string[] */
    protected $param;

    public function __construct(string $sourceName, callable $callback, array $param = [])
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
