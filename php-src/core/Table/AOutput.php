<?php

namespace kalanis\kw_table\core\Table;


use kalanis\kw_table\core\Table;


/**
 * Class AOutput
 * @package kalanis\kw_table\core\Table
 * Render output into...
 */
abstract class AOutput
{
    /** @var Table */
    protected $table = null;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    abstract public function render(): string;
}
