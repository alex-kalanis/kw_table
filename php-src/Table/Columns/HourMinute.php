<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class HourMinute
 * @package kalanis\kw_table\Table\Columns
 * Column will be formatted as hour:minute
 */
class HourMinute extends AColumn
{
    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function getValue(IRow $source)
    {
        $minutes = parent::getValue($source);

        if (empty($minutes)) {
            return '0:00';
        } else {
            if ($minutes < 0) {
                $addMinus = '-';
            } elseif ($minutes > 0) {
                $addMinus = '';
            } else {
                $addMinus = '';
            }

            $hours = floor(abs($minutes) / 60);
            $minutes = abs($minutes) - ($hours * 60);
            return $addMinus . ' ' . $hours . ':' . sprintf('%02d', $minutes);
        }
    }
}
