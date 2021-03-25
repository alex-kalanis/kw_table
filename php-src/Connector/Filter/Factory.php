<?php

namespace kalanis\kw_table\Connector\Filter;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Interfaces\Connector\IFilterType;


/**
 * Class Factory
 * @package kalanis\kw_table\Connector\Filter
 * Factory Class for accessing filter types
 */
class Factory
{
    const MAP_ARRAYS = 'array';
    const MAP_SEARCH = 'search';

    protected static $map = [
        self::MAP_ARRAYS => [
            'contains' => '\kalanis\kw_table\Connector\Filter\Arrays\Contains',
            'exact' => '\kalanis\kw_table\Connector\Filter\Arrays\Exact',
        ],
        self::MAP_SEARCH => [
            'contains' => '\kalanis\kw_table\Connector\Filter\Search\Contains',
            'exact' => '\kalanis\kw_table\Connector\Filter\Search\Exact',
            'range' => '\kalanis\kw_table\Connector\Filter\Search\Range',
        ],
    ];

    protected static $instance = null;

    public static function getInstance(): self
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct()
    {
    }

    /**
     * @param string $type
     * @param string $action
     * @return IFilterType
     * @throws MapperException
     */
    public function getFilter(string $type, string $action): IFilterType
    {
        if (!isset(static::$map[$type])) {
            throw new MapperException(sprintf('Unknown filter type *%s*!', $type));
        }
        if (!isset(static::$map[$type][$action])) {
            throw new MapperException(sprintf('Unknown filter action *%s* for type *%s*!', $action, $type));
        }
        $class = static::$map[$type][$action];
        return new $class();
    }
}
