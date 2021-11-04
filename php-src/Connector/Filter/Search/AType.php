<?php

namespace kalanis\kw_table\Connector\Filter\Search;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Interfaces\Connector\IFilterType;
use kalanis\kw_mapper\Search\Search;


/**
 * Class AType
 * @package kalanis\kw_table\Connector\Filter\Search
 */
abstract class AType implements IFilterType
{
    /** @var Search */
    protected $search;

    /**
     * @param Search $dataSource
     * @return $this
     * @throws MapperException
     */
    public function setDataSource($dataSource)
    {
        if (!$dataSource instanceof Search) {
            throw new MapperException('Param $dataSource must be an instance of \kalanis\kw_mapper\Search\Search.');
        }

        $this->search = $dataSource;
        return $this;
    }

    public function getDataSource()
    {
        return $this->search;
    }
}
