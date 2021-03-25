<?php

namespace kalanis\kw_table\Connector\Filter\Arrays;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Interfaces\Connector\IFilterType;
use kalanis\kw_table\Connector\Filter\Arrays;


/**
 * Class AType
 * @package kalanis\kw_table\Connector\Filter\Arrays
 */
abstract class AType implements IFilterType
{
    /** @var Arrays */
    protected $dataSource;

    /**
     * @param Arrays $dataSource
     * @return $this
     * @throws MapperException
     */
    public function setDataSource($dataSource)
    {
        if (!$dataSource instanceof Arrays) {
            throw new MapperException('Param $dataSource must be an instance of \kalanis\kw_table\Connector\Filter\Arrays.');
        }

        $this->dataSource = $dataSource;
        return $this;
    }
}
