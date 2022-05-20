<?php

namespace coreTests\Table;


use CommonTestClass;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\TableException;


class OutputTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNormal(): void
    {
        $lib = new Table();
        $lib->addColumn('id', new Columns\Basic('id'));

        $lib->addDataSetConnector(new Connector($this->basicData()));
        $lib->setOutput(new XOutput($lib));
        $this->assertNotEmpty($lib->getOutput());
        $this->assertEquals('here will be table content', $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNoOutput(): void
    {
        $lib = new Table(new Connector($this->basicData()));
        $lib->addColumn('id', new Columns\Basic('id'));

        $this->assertEmpty($lib->getOutput());
        $this->expectException(TableException::class);
        $this->expectExceptionMessage('Need to set output first!');
        $lib->render();
    }
}


class XOutput extends Table\AOutput
{
    public function render(): string
    {
        return 'here will be table content';
    }
}
