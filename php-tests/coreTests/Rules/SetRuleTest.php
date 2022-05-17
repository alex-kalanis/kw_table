<?php

namespace coreTests\Rules;


use CommonTestClass;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\core\TableException;


class SetRuleTest extends CommonTestClass
{
    /**
     * @throws TableException
     */
    public function testMatch(): void
    {
        $lib = new Rules\Set();
        $lib->addRule(new Rules\Always('first extra rule'));
        $lib->addRule(new Rules\Always('another extra rule'));

        $lib->allMustPass(false);
        $this->assertTrue($lib->validate('2'));

        $lib->allMustPass(true);
        $this->assertTrue($lib->validate('2'));

        $lib->addRule(new Rules\Negate(new Rules\Always('this will fail them')));
        $this->assertFalse($lib->validate('2'));

        $lib->allMustPass(false);
        $this->assertTrue($lib->validate('2'));
    }
}
