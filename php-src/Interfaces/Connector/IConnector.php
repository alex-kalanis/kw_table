<?php

namespace kalanis\kw_table\Interfaces\Connector;


use kalanis\kw_mapper\MapperException;


/**
 * Interface IConnector
 * @package kalanis\kw_table\Interfaces\Connector
 * Connect datasource to table representation and work with it
 */
interface IConnector
{
    /**
     * @param string $colName
     * @param string $value
     * @param IFilterType $type
     * @throws MapperException
     */
    public function setFiltering(string $colName, string $value, IFilterType $type): void;

    /**
     * @param string $colName
     * @param string $direction
     * @throws MapperException
     */
    public function setSorting(string $colName, string $direction): void;

    /**
     * @param int|null $offset
     * @param int|null $limit
     * @throws MapperException
     */
    public function setPagination(?int $offset, ?int $limit): void;

    /**
     * @return int
     * @throws MapperException
     */
    public function getTotalCount(): int;

    /**
     * @throws MapperException
     */
    public function fetchData(): void;

    /**
     * Type of filter with which will filter factory look on
     * @return string
     */
    public function getFilterType(): string;

    /**
     * Get cell content by preset key
     * @param string|int $key
     * @return mixed
     */
    public function getByKey($key);
}
