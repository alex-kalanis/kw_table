<?php

namespace kalanis\kw_table\core\Interfaces\Form;


use kalanis\kw_connect\core\Interfaces\IIterableConnector;


/**
 * Interface IField
 * @package kalanis\kw_table\core\Interfaces\Form
 * Single entry field in filter form
 */
interface IField
{
    /**
     * Alias of input
     * @param string $alias
     */
    public function setAlias(string $alias): void;

    /**
     * Add form input
     */
    public function add(): void;

    /**
     * CSS styles for each input
     * @param array<string, string> $attributes
     */
    public function setAttributes(array $attributes): void;

    /**
     * From which source it will read values
     * @param IIterableConnector $dataSource
     */
    public function setDataSourceConnector(IIterableConnector $dataSource): void;

    /**
     * Get filter which will modify results
     * @return string
     */
    public function getFilterAction(): string;
}
