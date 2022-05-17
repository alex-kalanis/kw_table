<?php

namespace kalanis\kw_table\core\Table;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\SingleVariable;
use kalanis\kw_connect\core\Interfaces\IOrder;
use kalanis\kw_table\core\Interfaces\Table\IColumn;


/**
 * Class Order
 * @package kalanis\kw_table\core\Table
 * It works two ways - check if desired column is used for ordering and fill header link for use it with another column
 */
class Order implements IOrder
{
    const PARAM_COLUMN = 'column';
    const PARAM_DIRECTION = 'direction';

    /** @var IColumn[] */
    protected $columns = [];
    /** @var Handler */
    protected $urlHandler = null;
    /** @var SingleVariable */
    protected $urlVariable = null;
    /** @var string */
    protected $currentColumnName = '';
    /** @var string */
    protected $currentDirection = self::ORDER_ASC;
    /** @var string[] */
    protected $primaryOrdering = [];
    /** @var string[][] */
    protected $ordering = [];

    public function __construct(Handler $urlHandler)
    {
        $this->urlHandler = $urlHandler;
        $this->urlVariable = new SingleVariable($this->urlHandler->getParams());
        $currentDirection = $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->getVariableValue();
        if ($this->isValidDirection($currentDirection)) {
            $this->currentDirection = $currentDirection;
            $this->currentColumnName = $this->urlVariable->setVariableName(static::PARAM_COLUMN)->getVariableValue();
        }
    }

    public function process(): self
    {
        if (empty($this->columns)) {
            return $this;
        }

        $columnName = $this->urlVariable->setVariableName(static::PARAM_COLUMN)->getVariableValue();
        $direction = $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->getVariableValue();
        if ($this->isValidDirection($direction)) {
            $this->currentDirection = $direction;
        }

        // fill primary ordering which will be shown in table
        if (array_key_exists($columnName, $this->columns)) {
            $this->currentColumnName = $columnName;
            $this->addPrimaryOrdering($this->currentColumnName, $this->currentDirection);
        } elseif (empty($this->ordering)) {
            $this->currentColumnName = $this->getFirstColumn()->getSourceName();
            $this->addPrimaryOrdering($this->currentColumnName, $this->currentDirection);
        }

        return $this;
    }

    protected function isValidDirection(string $direction): bool
    {
        return in_array($direction, [static::ORDER_ASC, static::ORDER_DESC]);
    }

    protected function getFirstColumn(): IColumn
    {
        return reset($this->columns);
    }

    protected function addPrimaryOrdering(string $columnName, string $direction)
    {
        $this->primaryOrdering = [$columnName, $direction];
    }

    public function getOrdering(): array
    {
        return empty($this->ordering) ? [$this->primaryOrdering] : $this->ordering;
    }

    /**
     * Basic ordering
     * @param string $columnName
     * @param string $direction
     */
    public function addOrdering(string $columnName, string $direction = self::ORDER_ASC)
    {
        $this->ordering[] = [$columnName, $direction];
    }

    /**
     * Add more important ordering
     * @param string $columnName
     * @param string $direction
     */
    public function addPrependOrdering(string $columnName, string $direction = self::ORDER_ASC)
    {
        array_unshift($this->ordering, [$columnName, $direction]);
    }

    public function addColumn(IColumn $column): self
    {
        $this->columns[$column->getSourceName()] = $column;
        return $this;
    }

    public function getHref(IColumn $column): ?string
    {
        if (!$this->isInOrder($column)) {
            return null;
        }

        $this->urlVariable->setVariableName(static::PARAM_COLUMN)->setVariableValue($column->getSourceName());
        $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->setVariableValue($this->getDirection($column));
        return $this->urlHandler->getAddress();
    }

    public function isInOrder(IColumn $column): bool
    {
        return array_key_exists($column->getSourceName(), $this->columns);
    }

    public function getDirection(IColumn $column): string
    {
        if ($this->isActive($column)) {
            if (static::ORDER_ASC == $this->currentDirection) {
                return static::ORDER_DESC;
            }
        }

        return static::ORDER_ASC;
    }

    public function getHeaderText(IColumn $header, string $leftSign = '*', string $rightSign = ''): string
    {
        if ($this->isActive($header)) {
            return $leftSign . $header->getHeaderText() . $rightSign;
        }

        return $header->getHeaderText();
    }

    public function isActive(IColumn $column): bool
    {
        return $column->getSourceName() == $this->currentColumnName;
    }
}
