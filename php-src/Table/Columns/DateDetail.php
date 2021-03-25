<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class DateDetail
 * @package kalanis\kw_table\Table\Columns
 * Extended date format in title
 */
class DateDetail extends Date
{
    public function getValue(IRow $source)
    {
        $result = $this->value($source, $this->sourceName);
        if(empty($result)){
            return 0;
        }else{
            return '<span title="'.date('Y-m-d H:i:s', $result).'">' . date($this->format, $result) . '</span>';
        }
    }
}
