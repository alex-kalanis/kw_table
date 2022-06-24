<?php

namespace coreTests\Connector;


use CommonTestClass;
use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_table\core\Connector\AMultipleValue;


class MultipleValueTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $lib = new XMultipleValue();

        $lib->setColumn('ijn');

        $this->assertEquals('', $lib->getAlias());
        $lib->setAlias('tfcuhb');
        $this->assertEquals('tfcuhb', $lib->getAlias());

        $this->assertEquals(null, $lib->getLabel());
        $lib->setLabel('rdctfv');
        $this->assertEquals('rdctfv', $lib->getLabel());
    }
}


class XMultipleValue extends AMultipleValue
{
    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
    }

    public function add(): void
    {
    }

    public function renderContent(): string
    {
        return '';
    }
}
