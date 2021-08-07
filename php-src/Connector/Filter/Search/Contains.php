<?php

namespace kalanis\kw_table\Connector\Filter\Search;


/**
 * Class Connector_Search_FilterType_Contains
 * @package Listing
 */
class Contains extends AType
{
    public function setFiltering(string $colName, $value)
    {
        if ('' !== $value) {
            $this->search->like($colName, $value);
        }
        return $this;
    }
}
