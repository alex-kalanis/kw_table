<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class Sprintf
 * @package kalanis\kw_table\core\Table\Columns
 * Add value into pre-formatted content
 */
class Sprintf extends AColumn
{
    use TEscapedValue;

    /** @var string */
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
