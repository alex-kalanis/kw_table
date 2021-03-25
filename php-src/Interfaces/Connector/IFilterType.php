<?php

namespace kalanis\kw_table\Interfaces\Connector;


use kalanis\kw_mapper\MapperException;


/**
 * Interface IFilterType
 * @package kalanis\kw_table\Interfaces\Connector
 * How the field affects search filter in datasource
 */
interface IFilterType
{
    const EMPTY_FILTER = '';

    /**
     * @param $dataSource
     * @return mixed
     * @throws MapperException
     */
    public function setDataSource($dataSource);

    /**
     * @param string $colName
     * @param mixed $value
     * @return mixed
     * @throws MapperException
     */
    public function setFiltering(string $colName, $value);
}
