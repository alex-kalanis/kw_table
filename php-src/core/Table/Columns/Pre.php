<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class Pre
 * @package kalanis\kw_table\core\Table\Columns
 * Preformatted content
 */
class Pre extends AColumn
{
    use TEscapedValue;

    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function getValue(IRow $source)
    {
        return nl2br(strval($this->valueEscape(parent::getValue($source))));
    }
}
