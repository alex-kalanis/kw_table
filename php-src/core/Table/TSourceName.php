<?php

namespace kalanis\kw_table\core\Table;


/**
 * Trait TSource
 * @package kalanis\kw_table\core\Table
 * Source name
 */
trait TSourceName
{
    /** @var string|int */
    protected $sourceName = '';

    /**
     * @param string|int $sourceName
     */
    public function setSourceName($sourceName): void
    {
        $this->sourceName = $sourceName;
    }

    /**
     * @return string|int
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }
}
