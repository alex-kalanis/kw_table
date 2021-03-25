<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Date
 * @package kalanis\kw_table\Table\Columns
 * Date formatted by preset value
 */
class Date extends AColumn
{
    protected $format = '';
    protected $timestamp = true;

    public function __construct(string $sourceName, string $format = 'Y-m-d', bool $timestamp = true)
    {
        $this->sourceName = $sourceName;
        $this->format = $format;
        $this->timestamp = $timestamp;
    }

    public function getValue(IRow $source)
    {
        $value = parent::getValue($source);
        if (empty($value)) {
            return 0;
        }
        if (!$this->timestamp) {
            $value = strtotime($value);
        }
        return date($this->format, $value);
    }
}
