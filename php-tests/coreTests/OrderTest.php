<?php

namespace coreTests;


use CommonTestClass;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources\Sources;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Order;


class OrderTest extends CommonTestClass
{
    public function testNormalColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar?column=id&direction=ASC');
        $lib = new Order(new Handler($src));

        $col = new Columns\Basic('name');
        $col->setHeaderText('top');
        $this->assertEmpty($lib->getHref($col));
        $this->assertEquals('top', $lib->getHeaderText($col, '!!! '));
        $lib->addColumn($col);
        $this->assertEquals('/foo/bar?column=name&direction=ASC', $lib->getHref($col));
        $this->assertEquals('top', $lib->getHeaderText($col, '!!! '));
    }

    public function testActiveColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar?column=id&direction=ASC');
        $lib = new Order(new Handler($src));

        $col = new Columns\Basic('id');
        $col->setHeaderText('top');
        $lib->addColumn($col);
        $this->assertEquals('/foo/bar?column=id&direction=DESC', $lib->getHref($col));
        $this->assertEquals('!!! top', $lib->getHeaderText($col, '!!! '));
    }

    public function testNoColumns(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar');

        $lib = new Order(new Handler($src));
        $lib->process();

        $ord = $lib->getOrdering();
        $this->assertEmpty(reset($ord));
    }

    public function testUnselectColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar');
        $col = new Columns\Basic('id');
        $col->setHeaderText('id');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col);
        $lib->process();

        $ord = $lib->getOrdering();
        $ordered = reset($ord);
        $this->assertEquals('id', $ordered[0]);
        $this->assertEquals('ASC', $ordered[1]);
    }

    public function testParamColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar?column=id&direction=DESC');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('id');
        $col2 = new Columns\Basic('name');
        $col2->setHeaderText('name');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col1);
        $lib->addColumn($col2);
        $lib->process();

        $ord = $lib->getOrdering();
        $ordered = reset($ord);
        $this->assertEquals('id', $ordered[0]);
        $this->assertEquals('DESC', $ordered[1]);
    }

    public function testSetColumn(): void
    {
        $src = new Sources();
        $src->setAddress('//foo/bar?column=name&direction=DESC');
        $col1 = new Columns\Basic('id');
        $col1->setHeaderText('id');
        $col2 = new Columns\Basic('name');
        $col2->setHeaderText('name');

        $lib = new Order(new Handler($src));
        $lib->addColumn($col1);
        $lib->addColumn($col2);
        $lib->addOrdering('name', 'ASC');
        $lib->addPrependOrdering('id', 'DESC');
        $lib->process();

        $ord = $lib->getOrdering();
        $ordered = reset($ord);
        $this->assertEquals('id', $ordered[0]);
        $this->assertEquals('DESC', $ordered[1]);
        $ordered = next($ord);
        $this->assertEquals('name', $ordered[0]);
        $this->assertEquals('ASC', $ordered[1]);
    }
}
