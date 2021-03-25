<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Pre
 * @package kalanis\kw_table\Table\Columns
 * Preformatted content
 */
class Pre extends AColumn
{
    use EscapedValueTrait;

    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function getValue(IRow $source)
    {
        return nl2br($this->valueEscape(parent::getValue($source)));
    }
}
