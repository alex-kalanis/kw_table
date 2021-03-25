<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Sprintf
 * @package kalanis\kw_table\Table\Columns
 * Add value into preformatted content
 */
class Sprintf extends AColumn
{
    use EscapedValueTrait;

    protected $format = '';

    public function __construct(string $sourceName, string $format)
    {
        $this->sourceName = $sourceName;
        $this->format = $format;
    }

    public function getValue(IRow $source)
    {
        return sprintf($this->format, $this->valueEscape(parent::getValue($source)));
    }
}
