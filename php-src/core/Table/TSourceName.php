<?php

namespace kalanis\kw_table\core\Table;


/**
 * Trait TSource
 * @package kalanis\kw_table\core\Table
 * Source name
 */
trait TSourceName
{
    protected $sourceName = '';

    public function setSourceName($sourceName): void
    {
        $this->sourceName = $sourceName;
    }

    public function getSourceName(): string
    {
        return $this->sourceName;
    }
}
