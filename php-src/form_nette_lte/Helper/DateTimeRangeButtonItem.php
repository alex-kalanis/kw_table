<?php

namespace kalanis\kw_table\form_nette_lte\Helper;


class DateTimeRangeButtonItem
{
    public $text;
    public $startJs;
    public $endJs;
    public $startTime;
    public $endTime;

    public function __construct($text, $startJs, $endJs, $startTime, $endTime)
    {
        $this->text = $text;
        $this->startJs = $startJs;
        $this->endJs = $endJs;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }
}
