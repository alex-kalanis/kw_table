<?php

namespace kalanis\kw_table\core\Table\Rules;


/**
 * Class ARule
 * @package kalanis\kw_table\core\Table\Rules
 * Abstract rule which should be filled from entry
 */
abstract class ARule
{
    /** @var string|int|null */
    protected $base;

    /**
     * @param string|int|null $base
     */
    public function __construct($base = null)
    {
        $this->base = $base;
    }
}
