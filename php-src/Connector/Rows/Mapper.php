<?php

namespace kalanis\kw_table\Connector\Rows;


use kalanis\kw_mapper\Records\ARecord;
use kalanis\kw_table\Interfaces\Table\IRow;


class Mapper implements IRow
{
    protected $record;

    public function __construct(ARecord $record)
    {
        $this->record = $record;
    }

    public function getValue($property)
    {
        if (method_exists($this->record, $property)) {
            return call_user_func([$this->record, $property]);
        } else {
            return $this->record->__get($property);
        }
    }
}
