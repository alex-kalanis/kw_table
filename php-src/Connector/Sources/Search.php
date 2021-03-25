<?php

namespace kalanis\kw_table\Connector\Sources;


use kalanis\kw_mapper\Records\ARecord;
use kalanis\kw_mapper\Search\Search as MapperSearch;
use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Connector\Rows\Mapper;
use kalanis\kw_table\Interfaces\Connector\IConnector;
use kalanis\kw_table\Interfaces\Connector\IFilterType;


/**
 * Class Search
 * @package kalanis\kw_table\Connector\Sources
 * Datasource is kw_mapper/Search
 */
class Search extends AConnector implements IConnector
{
    /** @var MapperSearch */
    public $dataSource;

    /** @var ARecord[] */
    protected $rawData = [];

    /** @var bool */
    protected $dataFetched = false;

    public function __construct(MapperSearch $search)
    {
        $this->dataSource = $search;
    }

    protected function parseData(): void
    {
        foreach ($this->rawData as $mapper) {
            $this->translatedData[] = new Mapper($mapper);
        }
    }

    public function setFiltering(string $colName, string $value, IFilterType $type): void
    {
        $type->setDataSource($this->dataSource);
        $type->setFiltering($colName, $value);
    }

    public function setSorting(string $colName, string $direction): void
    {
        $this->dataSource->orderBy($colName, $direction);
    }

    public function setPagination(?int $offset, ?int $limit): void
    {
        $this->dataSource->offset($offset);
        $this->dataSource->limit($limit);
    }

    public function getTotalCount(): int
    {
        return $this->dataSource->getCount();
    }

    public function fetchData(): void
    {
        $this->rawData = $this->dataSource->getResults();
        $this->parseData();
    }

    public function getFilterType(): string
    {
        return Factory::MAP_SEARCH;
    }
}
