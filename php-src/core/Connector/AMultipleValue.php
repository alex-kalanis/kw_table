<?php

namespace kalanis\kw_table\core\Connector;


use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_forms\Exceptions\RenderException;


/**
 * Class AMultipleValue
 * @package kalanis\kw_table\core\Connector
 * Connect multiple fields on one column in filter - abstract
 */
abstract class AMultipleValue
{
    /** @var string */
    protected $alias = '';
    /** @var string|null */
    protected $label = null;
    /** @var string */
    protected $columnName = '';

    public function setColumn(string $columnName): void
    {
        $this->columnName = $columnName;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    abstract public function getAlias(): string;

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    abstract public function setDataSourceConnector(IIterableConnector $dataSource): void;

    abstract public function add(): void;

    /**
     * @throws RenderException
     * @return string
     */
    abstract public function renderContent(): string;
}
