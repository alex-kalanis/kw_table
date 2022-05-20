<?php

namespace coreTests;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Table\AStyle;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\core\Table\TSourceName;


class StyleTest extends CommonTestClass
{
    public function testSourceName(): void
    {
        $lib = new XSourceName();
        $this->assertEquals('', $lib->getSourceName());
        $lib->setSourceName('id');
        $this->assertEquals('id', $lib->getSourceName());
    }

    public function testAttributes(): void
    {
        $lib = new XStyle();
        $lib->classArray([
            'foo' => new Rules\Exact(2), // class will be set only if source matches rule
            'bar' => null, // class will be set every time
            'value:name' => new Rules\Exact('def'), // class will be read from external value under rule // source: name; is "def"; put that in class
        ]);
        $lib->setSource('size');
        $this->assertEquals('class="bar"', $lib->getAttributes($this->getRow()));
        $lib->setSource('id');
        $this->assertEquals('class="foo bar"', $lib->getAttributes($this->getRow()));
        $lib->setSource('name');
        $this->assertEquals('class="bar def"', $lib->getAttributes($this->getRow()));
        iterator_to_array($lib);
    }

    public function testStyles(): void
    {
        $lib = new XStyle();
        $lib->style('foo', new Rules\Exact(2)); // class will be set only if source matches rule
        $lib->style('bar', null); // class will be set every time
        $lib->setSource('size');
        $this->assertEquals(' style="bar"', $lib->getCellStyle($this->getRow()));
        $lib->setSource('id');
        $this->assertEquals(' style="foo; bar"', $lib->getCellStyle($this->getRow()));
    }

    public function testStyleColors(): void
    {
        $lib = new XStyle();
        $lib->colorizeArray([
            'white' => new Rules\Exact(2),
            'gray' => null,
        ]);
        $lib->setSource('size');
        $this->assertEquals(' style="background-color: gray"', $lib->getCellStyle($this->getRow()));
        $lib->setSource('id');
        $this->assertEquals(' style="background-color: white; background-color: gray"', $lib->getCellStyle($this->getRow()));
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 2, 'name' => 'def', 'desc' => '<lang_to_"convert">', 'size' => 456, 'enabled' => 0]);
    }
}


class XStyle extends AStyle
{
    use TSourceName;

    public function setSource(string $source): void
    {
        $this->sourceName = $source;
    }
}


class XSourceName
{
    use TSourceName;
}
