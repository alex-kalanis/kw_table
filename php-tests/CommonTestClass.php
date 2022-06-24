<?php

use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\Interfaces\Table\IFilterMulti;
use kalanis\kw_table\core\Interfaces\Table\IFilterRender;
use PHPUnit\Framework\TestCase;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
    protected function basicData(): array
    {
        return [
            ['id' => 1, 'name' => 'abc', 'desc' => 'fill', 'size' => 123, 'enabled' => 1],
            ['id' => 2, 'name' => 'def', 'desc' => 'dude', 'size' => 456, 'enabled' => 0],
            ['id' => 3, 'name' => 'ghi', 'desc' => 'know', 'size' => 789, 'enabled' => 1],
            ['id' => 4, 'name' => 'jkl', 'desc' => 'hate', 'size' => 123, 'enabled' => 0],
            ['id' => 5, 'name' => 'mno', 'desc' => 'call', 'size' => 456, 'enabled' => 1],
            ['id' => 6, 'name' => 'pqr', 'desc' => 'that', 'size' => 789, 'enabled' => 0],
            ['id' => 7, 'name' => 'stu', 'desc' => 'life', 'size' => 321, 'enabled' => 0],
            ['id' => 8, 'name' => 'vwx', 'desc' => 'felt', 'size' => 654, 'enabled' => 1],
            ['id' => 9, 'name' => 'yz-', 'desc' => 'love', 'size' => 987, 'enabled' => 1],
        ];
    }
}


class XField implements IField
{
    public function setAlias(string $alias): void
    {
    }

    public function add(): void
    {
    }

    public function setAttributes(array $attributes): void
    {
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }
}


/**
 * Class XRenderField
 * When it's necessary to have own render output - callback and multiple input
 */
class XRenderField implements IField, IFilterRender
{
    protected $whatReturn = '';

    public function __construct($whatReturn = '')
    {
        $this->whatReturn = $whatReturn;
    }

    public function setAlias(string $alias): void
    {
    }

    public function add(): void
    {
    }

    public function setAttributes(array $attributes): void
    {
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    public function renderContent(): string
    {
        return $this->whatReturn;
    }
}


/**
 * Class XRenderMultiField
 * usually multiple fields which behaves as one
 */
class XRenderMultiField implements IField, IFilterRender, IFilterMulti
{
    protected $whatReturn = '';
    protected $pairs = [];

    public function __construct($whatReturn = '', $pairs = [])
    {
        $this->whatReturn = $whatReturn;
        $this->pairs = $pairs;
    }

    public function setAlias(string $alias): void
    {
    }

    public function add(): void
    {
    }

    public function setAttributes(array $attributes): void
    {
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    public function renderContent(): string
    {
        return $this->whatReturn;
    }

    public function getPairs(): array
    {
        return $this->pairs;
    }
}
