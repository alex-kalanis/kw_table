<?php

namespace kalanis\kw_table\Connector\Sources;


use kalanis\kw_mapper\Interfaces\IQueryBuilder;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Connector\Filter\Arrays as FilterArrays;
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
    protected $filteredData = [];
    /** @var string */
    protected $sortDirection = IQueryBuilder::ORDER_ASC;
    /** @var IFilterType[] */
    protected $filtering = [];
    /** @var int|null */
    protected $offset = null;
    /** @var int|null */
    protected $limit = null;

    public function __construct(array $source)
    {
        $this->dataSource = $source;
    }

    public function setFiltering(string $colName, string $value, IFilterType $type): void
    {
        $this->filtering[] = [$type, $colName, $value];
    }

    protected function getFiltered(&$data)
    {
        return new FilterArrays($data);
    }

    public function getTranslated($data): IRow
    {
        return new RowArray($data);
    }

    public function setSorting(string $colName, string $direction): void
    {
        $this->sorting[] = [$colName, $direction];
    }

    public function setPagination(?int $offset, ?int $limit): void
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getTotalCount(): int
    {
        if (empty($this->dataSource)) {
            return 0;
        }
        if (empty($this->filteredData)) {
            $this->fetchData();
        }
        return count($this->filteredData);
    }

    public function fetchData(): void
    {
        $this->parseData();
    }

    /**
     * @throws MapperException
     */
    protected function parseData(): void
    {
        $translated = array_map([$this, 'getTranslated'], $this->dataSource);
        $filtered = $this->getFiltered($translated);
        foreach (array_reverse($this->filtering) as list($type, $columnName, $value)) {
            /** @var IFilterType $type */
            $type->setDataSource($filtered);
            $type->setFiltering($columnName, $value);
            $filtered = $type->getDataSource();
        }

        foreach (array_reverse($this->sorting) as list($columnName, $direction)) {
            $toSort = $this->indexedArray($filtered, $columnName);
            if (IQueryBuilder::ORDER_DESC == $direction) {
                asort($toSort);
            } else {
                arsort($toSort);
            }
            $this->putItBack($filtered, $toSort);
        }

        $this->filteredData = $filtered->getArray();
        $this->translatedData = array_slice($filtered->getArray(), intval($this->offset), $this->limit);
    }

    /**
     * @param FilterArrays $filtered
     * @param string $columnName
     * @return array
     * @throws MapperException
     */
    protected function indexedArray(FilterArrays $filtered, string $columnName): array
    {
        $result = [];
        foreach ($filtered->getArray() as $index => $item) {
            $result[$index] = $item->getValue($columnName);
        }
        return $result;
    }

    protected function putItBack(FilterArrays $filtered, array $sorted): void
    {
        $finalArray = [];
        foreach ($sorted as $key => $item) {
            $finalArray[$key] = $filtered->offsetGet($key);
        }
        $filtered->setArray($finalArray);
    }

    public function getFilterType(): string
    {
        return Factory::MAP_ARRAYS;
    }
}
