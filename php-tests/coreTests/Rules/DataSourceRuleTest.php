<?php

namespace coreTests\Rules;


use CommonTestClass;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\core\TableException;


class DataSourceRuleTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testMatch(): void
    {
        $conn = new Connector($this->basicData());
        $conn->fetchData(); // because data translation
        $lib = new Rules\DataSourceSet();
        $lib->setDataSource($conn);

        $lib->addRule(new Rules\Always('first extra rule'), 'name');
        $lib->addRule(new Rules\Always('another extra rule'), 'desc');

        $lib->allMustPass(false);
        $this->assertTrue($lib->validate('unknown')); // pass because selected rules ignore the values

        $lib->allMustPass(true);
        $this->assertTrue($lib->validate(1));

        $lib->addRule(new Rules\Negate(new Rules\Always('this will fail them')), 'size');
        $this->assertFalse($lib->validate(2));

        $lib->allMustPass(false);
        $this->assertTrue($lib->validate(3));
    }
}
