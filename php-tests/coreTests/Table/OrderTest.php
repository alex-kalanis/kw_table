<?php

namespace coreTests\Table;


use CommonTestClass;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources\Sources;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IOrder;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Order;
use kalanis\kw_table\core\TableException;


class OrderTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNormal(): void
    {
        $lib = new Table();

        $src = new Sources();
        $src->setAddress('//foo/bar');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addOrderedColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('name', IOrder::ORDER_DESC);
        $lib->addPrimaryOrdering('id', IOrder::ORDER_ASC);

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->translateData();
        $this->assertNotEmpty($lib->getOrder());
        $this->assertEquals(9, $lib->rowCount());
        $this->assertEquals(3, $lib->colCount());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNoOrder(): void
    {
        $lib = new Table(new Connector($this->basicData()));
        $this->assertEmpty($lib->getOrder());
        $this->expectException(TableException::class);
        $this->expectExceptionMessage('Need to set order library first!');
        $lib->addOrderedColumn('id', new Columns\Basic('id'));
    }
}
