<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class Email
 * @package kalanis\kw_table\core\Table\Columns
 * Column contains an email, so make a link
 */
class Email extends AColumn
{
    public function __construct(string $sourceName)
    {
        $this->sourceName = $sourceName;
    }

    public function getValue(IRow $source)
    {
        return '<a href="mailto:' . parent::getValue($source) . '">' . parent::getValue($source) . '</a>';
    }
}
