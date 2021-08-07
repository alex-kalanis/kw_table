<?php

namespace kalanis\kw_table\Table;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\SingleVariable;
use kalanis\kw_mapper\Interfaces\IQueryBuilder;
use kalanis\kw_table\Interfaces\Table\IColumn;
use kalanis\kw_table\TableException;


/**
 * Class Sorter
 * @package kalanis\kw_table\Table
 * It works two ways - check if desired column is used for sorting and fill header link for use it with another column
 */
class Sorter implements IQueryBuilder
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

    public function getOrderings(): array
    {
        return empty($this->ordering) ? [$this->primaryOrdering] : $this->ordering;
    }

    public function addOrdering(string $columnName, string $direction = self::ORDER_ASC)
    {
        $this->ordering[] = [$columnName, $direction];
    }

    public function addPrependOrdering(string $columnName, string $direction = self::ORDER_ASC)
    {
        array_unshift($this->ordering, [$columnName, $direction]);
    }

    public function addPrimaryOrdering(string $columnName, string $direction = self::ORDER_ASC)
    {
        $this->primaryOrdering = [$columnName, $direction];
    }

    /**
     * @param string $columnName
     * @return bool
     * @throws TableException
     */
    protected function checkColumnName(string $columnName): bool
    {
        if (!array_key_exists($columnName, $this->columns)) {
            throw new TableException(sprintf('Column *%s* doesn\'t exist', $columnName));
        }
        return true;
    }

    /**
     * @param string $direction
     * @return bool
     * @throws TableException
     */
    protected function checkDirection(string $direction): bool
    {
        if (!$this->isValidDirection($direction)) {
            throw new TableException('Bad direction, set ASC or DESC');
        }
        return true;
    }

    protected function isValidDirection(string $direction): bool
    {
        return in_array($direction, [static::ORDER_ASC, static::ORDER_DESC]);
    }

    public function notEmpty(): bool
    {
        return !empty($this->columns);
    }

    public function addColumn(IColumn $column): self
    {
        $this->columns[$column->getSourceName()] = $column;
        return $this;
    }

    public function getHref(IColumn $column): ?string
    {
        if (!$this->isSorted($column)) {
            return null;
        }

        $this->urlVariable->setVariableName(static::PARAM_COLUMN)->setVariableValue($column->getSourceName());
        $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->setVariableValue($this->getDirection($column));
        return $this->urlHandler->getAddress();
    }

    public function isSorted(IColumn $column): bool
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

    public function fetch(): self
    {
        if (empty($this->columns)) {
            return $this;
        }

        $columnName = $this->urlVariable->setVariableName(static::PARAM_COLUMN)->getVariableValue();
        $direction = $this->urlVariable->setVariableName(static::PARAM_DIRECTION)->getVariableValue();
        if ($this->isValidDirection($direction)) {
            $this->currentDirection = $direction;
        }

        if (array_key_exists($columnName, $this->columns)) {
            $this->currentColumnName = $columnName;
            $this->addPrimaryOrdering($this->currentColumnName, $this->currentDirection);
        } elseif (empty($this->ordering)) {
            $this->currentColumnName = $this->getPrimaryOrder()->getSourceName();
            $this->addPrimaryOrdering($this->currentColumnName, $this->currentDirection);
        }

        return $this;
    }

    protected function getPrimaryOrder(): IColumn
    {
        return reset($this->columns);
    }
}
