<?php

namespace kalanis\kw_table\Connector\Sources;


use kalanis\kw_table\AIterator;
use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class AConnector
 * @package kalanis\kw_table\Connector\Sources
 */
abstract class AConnector extends AIterator
{
    protected $filterTypeDirectory = 'FilterType';

    /** @var IRow[] */
    protected $translatedData = [];

    protected function getIterableName(): string
    {
        return 'translatedData';
    }

    abstract protected function parseData(): void;

    /**
     * Get row with data by preset key
     * @param $key
     * @return IRow
     */
    public function getByKey($key): IRow
    {
        return $this->translatedData[$key];
    }
}
