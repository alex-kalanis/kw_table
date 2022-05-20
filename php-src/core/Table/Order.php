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
 *
 * The implementation is simple
 * First array is from system defined by programmer ($this->ordering)
 * If there is none, then get array of columns ($this->>columns)
 * Then prepend params from handler ($this->>currentDirection, $this->currentColumnName) if they contains anything usable
 *
 * First from this list is an active one ($this->primaryOrdering / $this->>activeOrdering) and will be used for compare
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
    protected $masterColumnName = '';
    /** @var string */
    protected $masterDirection = '';
    /** @var string */
    protected $addressColumnName = '';
    /** @var string */
    protected $addressDirection = '';
    /** @var string[][] */
    protected $ordering = [];

    public function __construct(Handler $urlHandler)
    {
        $this->urlHandler = $urlHandler;
        $this->urlVariable = new SingleVariable($this->urlHandler->getParams());
    }

    public function process(): self
    {
        if (empty($this->columns)) {
            return $this;
        }

        $defaultDirection = static::ORDER_ASC; // info about default direction - can be passed from address handler
        $addrDirection = $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->getVariableValue();
        $addrColumnName = $this->urlVariable->setVariableName(static::PARAM_COLUMN)->getVariableValue();
        if ($this->isValidDirection($addrDirection)) {
            $this->addressDirection = $defaultDirection = $addrDirection;
            $this->addressColumnName = $addrColumnName;
        }

        $this->ordering = array_filter($this->ordering, [$this, 'checkOrder']);

        if (empty($this->ordering)) {
            foreach ($this->columns as $item) {
                $this->addOrdering($item->getSourceName(), $defaultDirection);
            }
        }

        if (!empty($this->addressColumnName) && $this->checkColumn($this->addressColumnName)) {
            $this->addPrependOrdering($this->addressColumnName, $this->addressDirection);
        }

        $first = reset($this->ordering);
        $this->masterColumnName = $first[0];
        $this->masterDirection = $first[1];

        return $this;
    }

    protected function isValidDirection(string $direction): bool
    {
        return in_array($direction, [static::ORDER_ASC, static::ORDER_DESC]);
    }

    public function checkOrder(array $ordering): bool
    {
        return $this->checkColumn(reset($ordering));
    }

    public function checkColumn(string $columnName): bool
    {
        return array_key_exists($columnName, $this->columns);
    }

    public function getOrdering(): array
    {
        return $this->ordering;
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
        $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->setVariableValue($this->getActiveDirection($column));
        return $this->urlHandler->getAddress();
    }

    public function isInOrder(IColumn $column): bool
    {
        return $this->checkColumn($column->getSourceName());
    }

    public function getActiveDirection(IColumn $column): string
    {
        if ($this->isActive($column)) {
            if (static::ORDER_ASC == $this->masterDirection) {
                return static::ORDER_DESC;
            }
        }

        return static::ORDER_ASC;
    }

    public function getHeaderText(IColumn $header, string $leftSign = '*', string $rightSign = ''): string
    {
        return $this->isActive($header)
            ? $leftSign . $header->getHeaderText() . $rightSign
            : $header->getHeaderText()
        ;
    }

    public function isActive(IColumn $column): bool
    {
        return $column->getSourceName() == $this->masterColumnName;
    }

    public function getMasterColumnName(): string
    {
        return $this->masterColumnName;
    }

    public function getMasterDirection(): string
    {
        return $this->masterDirection;
    }

    public function getAddressColumnName(): string
    {
        return $this->addressColumnName;
    }

    public function getAddressDirection(): string
    {
        return $this->addressDirection;
    }
}
