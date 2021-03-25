<?php

namespace kalanis\kw_table\Interfaces\Connector;


use kalanis\kw_mapper\MapperException;


/**
 * Interface IField
 * @package kalanis\kw_table\Interfaces
 * Single entry field in filter form
 */
interface IField
{
    /**
     * Alias of input
     * @param string $alias
     */
    public function setAlias(string $alias): void;

    /**
     * Add form input
     */
    public function add(): void;

    /**
     * CSS styles for each input
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    /**
     * From which source it will read values
     * @param IConnector $dataSource
     */
    public function setDataSource(IConnector $dataSource): void;

    /**
     * Get filter which will modify results
     * @return IFilterType
     * @throws MapperException
     */
    public function getFilterType(): IFilterType;
}
