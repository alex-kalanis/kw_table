<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class DateTime
 * @package kalanis\kw_table\Table\Columns
 * Format date by datetime class
 */
class DateTime extends AColumn
{
    protected $format = '';
    protected $timestamp = false;
    protected $dateTime;

    public function __construct(string $sourceName, string $format = 'Y-m-d', bool $timestamp = false, \DateTime $dateTime = null)
    {
        $this->sourceName = $sourceName;
        $this->format = $format;
        $this->timestamp = $timestamp;
        $this->dateTime = $dateTime ? $dateTime : new \DateTime();
    }

    public function getValue(IRow $source)
    {
        $result = parent::getValue($source);
        $isEmpty = empty($result);
        if ($isEmpty) {
            return 0;
        } else {
            $result = $this->timestamp ? $result : strtotime($result);
            $this->dateTime->setTimestamp($result);

            return $this->dateTime->format($this->format);
        }
    }
}
