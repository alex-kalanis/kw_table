<?php

namespace coreTests;


use CommonTestClass;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources\Sources;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Order;


class OrderTest extends CommonTestClass
{
    public function testNoColumns(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('top id');

        $lib = new Order(new Handler($src));
        $lib->process();

        $ord = $lib->getOrdering();
        $this->assertEmpty(reset($ord));
        $this->assertEmpty($lib->getAddressColumnName());
        $this->assertEmpty($lib->getAddressDirection());
        $this->assertEmpty($lib->getMasterColumnName());
        $this->assertEmpty($lib->getMasterDirection());

        $this->assertEmpty($lib->getHref($col1));
        $this->assertEquals('top id', $lib->getHeaderText($col1, '!!! '));
    }

    public function testUnselectedColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('top id');
        $col2 = new Columns\Basic('name');
        $col2->setHeaderText('top name');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col1);
        $lib->addColumn($col2);
        $lib->process();

        $ord = $lib->getOrdering();
        $ordered = reset($ord);
        $this->assertEquals('id', $ordered->getColumnName());
        $this->assertEquals('ASC', $ordered->getProperty());
        $ordered = next($ord);
        $this->assertEquals('name', $ordered->getColumnName());
        $this->assertEquals('ASC', $ordered->getProperty());
        $this->assertTrue(false === next($ord));

        $this->assertEmpty($lib->getAddressColumnName());
        $this->assertEmpty($lib->getAddressDirection());
        $this->assertEquals('id', $lib->getMasterColumnName());
        $this->assertEquals('ASC', $lib->getMasterDirection());

        $this->assertEquals('/foo/bar?column=id&direction=DESC', $lib->getHref($col1));
        $this->assertEquals('!!! top id', $lib->getHeaderText($col1, '!!! '));
        $this->assertEquals('/foo/bar?column=name&direction=ASC', $lib->getHref($col2));
        $this->assertEquals('top name', $lib->getHeaderText($col2, '!!! '));
    }

    public function testParamColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar?column=id&direction=DESC');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('top id');
        $col2 = new Columns\Basic('name');
        $col2->setHeaderText('top name');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col1);
        $lib->addColumn($col2);
        $lib->process();

        // no order entry defined, so it will be columns plus direction
        // see that direction is written as default for columns ordering
        $ord = $lib->getOrdering();
        $ordered = reset($ord);
        $this->assertEquals('id', $ordered->getColumnName());
        $this->assertEquals('DESC', $ordered->getProperty());
        $ordered = next($ord);
        $this->assertEquals('id', $ordered->getColumnName());
        $this->assertEquals('DESC', $ordered->getProperty());
        $ordered = next($ord);
        $this->assertEquals('name', $ordered->getColumnName());
        $this->assertEquals('DESC', $ordered->getProperty());
        $this->assertTrue(false === next($ord));

        $this->assertEquals('id', $lib->getAddressColumnName());
        $this->assertEquals('DESC', $lib->getAddressDirection());
        $this->assertEquals('id', $lib->getMasterColumnName());
        $this->assertEquals('DESC', $lib->getMasterDirection());

        $this->assertEquals('/foo/bar?column=id&direction=ASC', $lib->getHref($col1));
        $this->assertEquals('!!! top id', $lib->getHeaderText($col1, '!!! '));
        $this->assertEquals('/foo/bar?column=name&direction=ASC', $lib->getHref($col2));
        $this->assertEquals('top name', $lib->getHeaderText($col2, '!!! '));
    }

    public function testSetColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('top id');
        $col2 = new Columns\Basic('name');
        $col2->setHeaderText('top name');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col1);
        $lib->addColumn($col2);
        $lib->addOrdering('name', 'ASC');
        $lib->addPrependOrdering('id', 'DESC');
        $lib->addOrdering('xcrem', 'ASC'); // this one is undefined - invalid and will be kicked out
        $lib->process();

        $ord = $lib->getOrdering();
        $ordered = reset($ord);
        $this->assertEquals('id', $ordered->getColumnName());
        $this->assertEquals('DESC', $ordered->getProperty());
        $ordered = next($ord);
        $this->assertEquals('name', $ordered->getColumnName());
        $this->assertEquals('ASC', $ordered->getProperty());
        $this->assertTrue(false === next($ord));

        $this->assertEmpty($lib->getAddressColumnName());
        $this->assertEmpty($lib->getAddressDirection());
        $this->assertEquals('id', $lib->getMasterColumnName());
        $this->assertEquals('DESC', $lib->getMasterDirection());

        $this->assertEquals('/foo/bar?column=id&direction=ASC', $lib->getHref($col1));
        $this->assertEquals('!!! top id', $lib->getHeaderText($col1, '!!! '));
        $this->assertEquals('/foo/bar?column=name&direction=ASC', $lib->getHref($col2));
        $this->assertEquals('top name', $lib->getHeaderText($col2, '!!! '));
    }

    public function testSetParamColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar?column=name&direction=DESC');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('top id');
        $col2 = new Columns\Basic('name');
        $col2->setHeaderText('top name');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col1);
        $lib->addColumn($col2);
        $lib->addOrdering('name', 'ASC');
        $lib->addPrependOrdering('id', 'DESC');
        $lib->process();

        // ordering by preset columns, not just that defined ones
        $ord = $lib->getOrdering();
        $ordered = reset($ord); // first from address
        $this->assertEquals('name', $ordered->getColumnName());
        $this->assertEquals('DESC', $ordered->getProperty());
        $ordered = next($ord); // then from definitions
        $this->assertEquals('id', $ordered->getColumnName());
        $this->assertEquals('DESC', $ordered->getProperty());
        $ordered = next($ord);
        $this->assertEquals('name', $ordered->getColumnName());
        $this->assertEquals('ASC', $ordered->getProperty());
        $this->assertTrue(false === next($ord));

        $this->assertEquals('name', $lib->getAddressColumnName());
        $this->assertEquals('DESC', $lib->getAddressDirection());
        $this->assertEquals('name', $lib->getMasterColumnName());
        $this->assertEquals('DESC', $lib->getMasterDirection());

        $this->assertEquals('/foo/bar?column=id&direction=ASC', $lib->getHref($col1));
        $this->assertEquals('top id', $lib->getHeaderText($col1, '!!! '));
        $this->assertEquals('/foo/bar?column=name&direction=ASC', $lib->getHref($col2));
        $this->assertEquals('!!! top name', $lib->getHeaderText($col2, '!!! '));
    }
}
