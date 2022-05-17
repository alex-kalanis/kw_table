<?php

namespace coreTests\Columns;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table\Columns;


class DateColumnTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     */
    public function testDate(): void
    {
        $lib = new Columns\Date('add', 'Y-m-d', false);
        $this->assertEquals('2022-05-10', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testDateStamp(): void
    {
        $lib = new Columns\Date('from');
        $this->assertEquals('2022-05-08', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testDateEmpty(): void
    {
        $lib = new Columns\Date('to');
        $this->assertEmpty($lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testTime(): void
    {
        $lib = new Columns\DateTime('add');
        $this->assertEquals('2022-05-10', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testTimeStamp(): void
    {
        $lib = new Columns\DateTime('from', 'Y-m-d', true);
        $this->assertEquals('2022-05-08', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testTimeEmpty(): void
    {
        $lib = new Columns\DateTime('to');
        $this->assertEmpty($lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testDateDetail(): void
    {
        $lib = new Columns\DateDetail('from');
        $this->assertEquals('<span title="2022-05-08 08:53:20">2022-05-08</span>', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testDateDetailEmpty(): void
    {
        $lib = new Columns\DateDetail('to');
        $this->assertEmpty($lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testHours(): void
    {
        $lib = new Columns\HourMinute('min');
        $this->assertEquals('11:21', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testHoursEmpty(): void
    {
        $lib = new Columns\HourMinute('to');
        $this->assertEquals('0:00', $lib->getValue($this->getRow()));
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 4, 'name' => 'def', 'from' => 1652000000, 'to' => 0, 'add' => '2022-05-10 22:08', 'min' => 681, 'enabled' => 1]);
    }
}
