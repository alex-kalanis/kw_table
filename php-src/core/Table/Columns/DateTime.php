<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class DateTime
 * @package kalanis\kw_table\core\Table\Columns
 * Format date by datetime class
 */
class DateTime extends AColumn
{
    /** @var string */
    protected $format = '';
    /** @var bool */
    protected $timestamp = false;
    /** @var \DateTime */
    protected $dateTime;

    public function __construct(string $sourceName, string $format = 'Y-m-d', bool $timestamp = false, \DateTime $dateTime = null)
    {
        $this->sourceName = $sourceName;
        $this->format = $format;
        $this->timestamp = $timestamp;
        $this->dateTime = $dateTime ?: new \DateTime();
    }

    public function getValue(IRow $source)
    {
        $result = parent::getValue($source);
        $isEmpty = empty($result);
        if ($isEmpty) {
            return 0;
        } else {
            $result = $this->timestamp ? $result : strtotime(strval($result));
            $this->dateTime->setTimestamp(intval($result));

            return $this->dateTime->format($this->format);
        }
    }
}
