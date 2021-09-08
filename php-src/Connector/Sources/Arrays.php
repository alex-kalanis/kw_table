<?php

namespace kalanis\kw_table\Connector\Sources;


use kalanis\kw_mapper\Interfaces\IQueryBuilder;
use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Connector\Rows\Arrays as RowArray;
use kalanis\kw_table\Interfaces\Connector\IConnector;
use kalanis\kw_table\Interfaces\Connector\IFilterType;
use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class Arrays
 * @package kalanis\kw_table\Connector\Sources
 * For likes there is a column finder in search mapper.
 * So it's possible to map children for sorting and filtering.
 */
class Arrays extends AConnector implements IConnector
{
    /** @var array */
    protected $dataSource = [];
    /** @var string[][] */
    protected $sorting = [];
    /** @var array */
    protected $rawData = [];

    public function __construct(array $source)
    {
        $this->dataSource = $source;
    }

    public function setFiltering(string $colName, string $value, IFilterType $type): void
    {
        $type->setDataSource($this->getTranslated($this->dataSource));
        $type->setFiltering($colName, $value);
    }

    protected function getTranslated($data): IRow
    {
        return new RowArray($data);
    }

    public function setSorting(string $colName, string $direction): void
    {
        $this->sorting[] = [$colName, $direction];
    }

    public function setPagination(?int $offset, ?int $limit): void
    {
        $this->dataSource = array_slice($this->dataSource, intval($offset), $limit);
    }

    public function getTotalCount(): int
    {
        return count($this->dataSource);
    }

    public function fetchData(): void
    {
        $this->rawData = $this->dataSource;
        $this->parseData();
    }

    protected function parseData(): void
    {
        foreach (array_reverse($this->sorting) as list($columnName, $direction)) {
            $phpSort = (IQueryBuilder::ORDER_DESC == $direction) ? SORT_DESC : SORT_ASC;
            array_multisort(array_column($this->rawData, $columnName), $phpSort, $this->rawData);
        }
        foreach ($this->rawData as $array) {
            $this->translatedData[] = new RowArray($array);
        }
    }

    public function getFilterType(): string
    {
        return Factory::MAP_ARRAYS;
    }
}
