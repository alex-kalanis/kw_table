<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Currency
 * @package kalanis\kw_table\Table\Columns
 * Output format as simple currency
 * For more currency options use Sprintf
 */
class Currency extends AColumn
{
    protected $currency = '';

    public function __construct(string $sourceName, string $currency)
    {
        $this->sourceName = $sourceName;
        $this->currency = $currency;
    }

    public function getValue(IRow $source)
    {
        $value = parent::getValue($source);
        return $value . ' ' . $this->currency;
    }
}
