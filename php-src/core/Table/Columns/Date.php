<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class Date
 * @package kalanis\kw_table\core\Table\Columns
 * Date formatted by preset value
 */
class Date extends AColumn
{
    /** @var string */
    protected $format = '';
    /** @var bool */
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
            $value = strtotime(strval($value));
        }
        return date($this->format, intval($value));
    }
}
