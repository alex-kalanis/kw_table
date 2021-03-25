<?php

namespace kalanis\kw_table\Interfaces\Connector;


use kalanis\kw_mapper\MapperException;


/**
 * Interface IForm
 * @package kalanis\kw_table\Interfaces\Connector
 * Accessing filter form in the table
 */
interface IForm
{
    /**
     * Add entry which will modify search params in table datasource
     * @param IField $field
     * @throws MapperException
     */
    public function addField(IField $field): void;

    /**
     * Set value to the form entry
     * @param string $alias
     * @param string|int $value
     */
    public function setValue(string $alias, $value): void;

    /**
     * Get all values in form
     * @return string[]|int[]
     */
    public function getValues(): array;

    /**
     * Get value from form by entry alias
     * @param string $alias
     * @return mixed
     */
    public function getValue(string $alias);

    /**
     * Get form name, usually for distinction in scripts in render
     * @return string
     */
    public function getFormName(): string;

    /**
     * Beginning of form
     * @return string
     */
    public function renderStart(): string;

    /**
     * End of form
     * @return string
     */
    public function renderEnd(): string;

    /**
     * Single form entry
     * @param string $alias
     * @return string
     */
    public function renderField(string $alias): string;
}
