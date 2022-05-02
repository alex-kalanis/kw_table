<?php

namespace kalanis\kw_table\core\Table\Rules;


/**
 * Class ARule
 * @package kalanis\kw_table\core\Table\Rules
 * Abstract rule which should be filled from entry
 */
abstract class ARule
{
    protected $base;

    public function __construct($base = null)
    {
        $this->base = $base;
    }
}
