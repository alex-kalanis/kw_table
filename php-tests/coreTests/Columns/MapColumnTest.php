<?php

namespace coreTests\Columns;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table\Columns;


class MapColumnTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     */
    public function testMapTrans(): void
    {
        $lib = new Columns\Map('here', $this->mapTrans(), 'not here');
        $this->assertEquals('pasive', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testMapNot(): void
    {
        $lib = new Columns\Map('far', $this->mapTrans(), 'not here');
        $this->assertEquals('not here', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testMapWtf(): void
    {
        $lib = new Columns\Map('out', $this->mapTrans(), 'not here');
        $this->assertEquals('456', $lib->getValue($this->getRow()));
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 2, 'here' => 1, 'far' => 0, 'out' => 456]);
    }

    protected function mapTrans(): array
    {
        return [
            1 => 'pasive',
            2 => 'active',
            3 => 'roar',
        ];
    }
}
