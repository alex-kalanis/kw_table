<?php

namespace kalanis\kw_table\core\Table\Internal;


use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class Attributes
 * @package kalanis\kw_table\core\Table\Internal
 * Styled attributes
 */
class Attributes
{
    /** @var int|string */
    protected $columnName = '';
    /** @var string */
    protected $property = '';
    /** @var IRule|null */
    protected $condition = null;

    /**
     * @param string|int $columnName
     * @param string $property
     * @param IRule|null $condition
     */
    public function __construct($columnName = '', string $property = '', ?IRule $condition = null)
    {
        $this->columnName = $columnName;
        $this->property = $property;
        $this->condition = $condition;
    }

    /**
     * @return int|string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getCondition(): ?IRule
    {
        return $this->condition;
    }
}
