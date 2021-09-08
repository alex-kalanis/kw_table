<?php

namespace kalanis\kw_table\Connector\Sources;


use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Connector\Rows\Mapper as RowMapper;
use kalanis\kw_table\Interfaces\Connector\IConnector;
use kalanis\kw_table\Interfaces\Connector\IFilterType;
use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Mapper
 * @package kalanis\kw_table\Connector\Sources
 * Datasource is kw_mapper/Record
 */
class Mapper extends AConnector implements IConnector
{
    /** @var array */
    protected $dataSource;
    /** @var array */
    protected $rawData = [];

    public function __construct(array $records)
    {
        $this->dataSource = $records;
    }

    protected function parseData(): void
    {
        foreach ($this->rawData as $record) {
            $this->translatedData[] = $this->getTranslated($record);
        }
    }

    protected function getTranslated($data): IRow
    {
        return new RowMapper($data);
    }

    public function setFiltering(string $colName, string $value, IFilterType $type): void
    {
    }

    public function setSorting(string $colName, string $direction): void
    {
    }

    public function setPagination(?int $offset, ?int $limit): void
    {
    }

    public function getTotalCount(): int
    {
        return intval(!empty($this->rawData));
    }

    public function fetchData(): void
    {
        $this->rawData = $this->dataSource;
        $this->parseData();
    }

    public function getFilterType(): string
    {
        return Factory::MAP_ARRAYS;
    }
}
