<?php

namespace kalanis\kw_table\core\Connector;


use kalanis\kw_connect\core\Interfaces\IConnector;
use kalanis\kw_forms\Exceptions\RenderException;


/**
 * Class AMultipleValue
 * @package kalanis\kw_table\core\Connector
 * Connect multiple fields on one column in filter - abstract
 */
abstract class AMultipleValue
{
    protected $alias = '';
    protected $label = null;
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

    abstract public function setDataSourceConnector(IConnector $dataSource): void;

    abstract public function add(): void;

    /**
     * @return string
     * @throws RenderException
     */
    abstract public function renderContent(): string;
}
