<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class EscFunc
 * @package kalanis\kw_table\Table\Columns
 * Each row in Column will pass through external function - this one is escaped
 */
class EscFunc extends AColumn
{
    use EscapedValueTrait;

    /** @var callable */
    protected $callback;

    public function __construct(string $sourceName, callable $callback)
    {
        $this->sourceName = $sourceName;
        $this->callback = $callback;
    }

    public function getValue(IRow $source)
    {
        return $this->valueEscape(call_user_func($this->callback, parent::getValue($source)));
    }
}