<?php

namespace kalanis\kw_table\Table\Output;


use kalanis\kw_table\Table;


/**
 * Class AOutput
 * @package kalanis\kw_table\Table\Output
 * Render output into...
 */
abstract class AOutput
{
    protected $table = null;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    abstract function render(): string;
}
