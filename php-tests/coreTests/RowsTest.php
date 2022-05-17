<?php

namespace coreTests;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_table\core\Table\Columns\Basic;
use kalanis\kw_table\core\Table\Rows;
use kalanis\kw_table\core\Table\Internal;


class RowsTest extends CommonTestClass
{
    public function testRowStyled(): void
    {
        $lib = new Internal\Row();
        $this->assertEmpty($lib->getSource());
        $src = $this->getRow();
        $lib->setSource($src);
        $this->assertEquals($src, $lib->getSource());
        $lib->addColumn(new Basic('foo'));
    }

    public function testRowAb(): void
    {
        $lib = new XRow();
        $this->assertEmpty($lib->getFunctionName());
        $this->assertEmpty($lib->getFunctionArgs());
        $lib->setFunctionName('substr');
        $this->assertEquals('substr', $lib->getFunctionName());
        $lib->setFunctionArgs(['nop', 'ouf']);
        $this->assertEquals(['nop', 'ouf'], $lib->getFunctionArgs());
    }

    public function testRowFn(): void
    {
        $lib = new Rows\FunctionRow('substr', ['foo', 'bar']);
        $this->assertEquals('substr', $lib->getFunctionName());
        $this->assertEquals(['foo', 'bar'], $lib->getFunctionArgs());
    }

    public function testRowTbl(): void
    {
        $lib = new Rows\TableRow('substr', ['foo', 'bar']);
        $this->assertEquals('substr', $lib->getFunctionName());
        $this->assertEquals(['foo', 'bar'], $lib->getFunctionArgs());
    }

    public function testRowCl(): void
    {
        $lib = new Rows\ClassRow('preferred', 'some rule', 'where');
        $this->assertEquals('class', $lib->getFunctionName());
        $this->assertEquals(['preferred', 'some rule', 'where'], $lib->getFunctionArgs());
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 2, 'name' => 'def', 'desc' => '<lang_to_"convert">', 'size' => 456, 'enabled' => 0]);
    }
}


class XRow extends Rows\ARow
{
}
