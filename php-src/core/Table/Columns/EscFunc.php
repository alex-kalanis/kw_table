<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class EscFunc
 * @package kalanis\kw_table\core\Table\Columns
 * Each row in Column will pass through external function - this one is escaped
 */
class EscFunc extends AColumn
{
    use TEscapedValue;

    /** @var callable */
    protected $callback;

    /**
     * @param string $sourceName
     * @param callable $callback
     */
    public function __construct(string $sourceName, $callback)
    {
        $this->sourceName = $sourceName;
        $this->callback = $callback;
    }

    public function getValue(IRow $source)
    {
        return $this->valueEscape(call_user_func($this->callback, parent::getValue($source)));
    }
}