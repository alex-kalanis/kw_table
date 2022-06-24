<?php

namespace kalanis\kw_table\core\Interfaces\Table;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Form\IField;


/**
 * Interface IColumn
 * @package kalanis\kw_table\core\Interfaces\Table
 * Simple column in table
 *
 * The filters on header affects sorting, the filters on bottom is for actions over columns
 */
interface IColumn
{
    /**
     * Obtained value - formatted
     * @param IRow $source
     * @throws ConnectException
     * @return string
     */
    public function translate(IRow $source): string;

    /**
     * Obtained value - usually raw one
     * @param IRow $source
     * @return mixed
     */
    public function getValue(IRow $source);

    /**
     * Source key which is used in search
     * @return string|int
     */
    public function getSourceName();

    /**
     * Filter key which is used in filters
     * @return string
     */
    public function getFilterName(): string;

    /**
     * Can be results sorted by this column?
     * @return bool
     */
    public function canOrder(): bool;

    /**
     * Set different header text than source name
     * @param string $text
     * @return $this
     */
    public function setHeaderText(string $text);

    /**
     * Get header text
     * @return string
     */
    public function getHeaderText(): string;

    /**
     * Contains filters on head?
     * @return bool
     */
    public function hasHeaderFilterField(): bool;

    /**
     * Get that head filter field
     * @return IField|null
     */
    public function getHeaderFilterField(): ?IField;

    /**
     * Set header filter field
     * @param IField $field
     * @return $this
     */
    public function setHeaderFiltering(IField $field);

    /**
     * Contains filters on foot?
     * @return bool
     */
    public function hasFooterFilterField(): bool;

    /**
     * Get that footer filter field
     * @return IField|null
     */
    public function getFooterFilterField(): ?IField;

    /**
     * Set footer filter field
     * @param IField $field
     * @return $this
     */
    public function setFooterFiltering(IField $field);

}
