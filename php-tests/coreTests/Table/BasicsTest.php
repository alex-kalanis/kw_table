<?php

namespace coreTests\Table;


use CommonTestClass;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\TableException;


class BasicsTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNormal(): void
    {
        $lib = new Table();
        $this->assertEmpty($lib->getColumns());
        $this->assertEmpty($lib->getTableData());

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));
        $this->assertNotEmpty($lib->getColumns());
        $this->assertEmpty($lib->getTableData());
        $this->assertNotNull($lib->getDataSetConnector());
        $this->assertFalse($lib->showPagerOnHead());
        $this->assertTrue($lib->showPagerOnFoot());

        $lib->translateData();
        $this->assertNotEmpty($lib->getColumns());
        $this->assertNotEmpty($lib->getTableData());
        $this->assertEquals(9, $lib->rowCount());
        $this->assertEquals(3, $lib->colCount());

        $this->assertNotNull($lib->getColumn(2));
        $this->assertNull($lib->getColumn(6));
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNoColumn(): void
    {
        $lib = new Table(new Connector($this->basicData()));
        $this->expectException(TableException::class);
        $this->expectExceptionMessage('You need to define at least one column');
        $lib->render();
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNoDataSource(): void
    {
        $lib = new Table();
        $lib->addColumn('id', new Columns\Basic('id'));

        $this->expectException(TableException::class);
        $this->expectExceptionMessage('Cannot create table from empty dataset');
        $lib->render();
    }

    /**
     * @throws ConnectException
     */
    public function testClasses(): void
    {
        $lib = new Table();
        $this->assertEquals(['table', 'table-bordered', 'table-striped', 'table-hover', 'table-condensed', 'bootstrap-datatable', 'listtable'], $lib->getClasses());
        $this->assertEquals('table table-bordered table-striped table-hover table-condensed bootstrap-datatable listtable', $lib->getClassesInString());
        // kick them out
        $lib->removeClass('table');
        $lib->removeClass('table-bordered');
        $lib->removeClass('table-striped');
        $lib->removeClass('table-hover');
        $lib->removeClass('table-condensed');
        $lib->removeClass('bootstrap-datatable');
        $lib->removeClass('listtable');
        $this->assertEmpty($lib->getClasses());
        $this->assertEquals('', $lib->getClassesInString());
        $lib->addClass('free-fun');
        $this->assertEquals('free-fun', $lib->getClassesInString());
    }
}
