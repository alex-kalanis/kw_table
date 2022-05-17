<?php

namespace coreTests\Rules;


use CommonTestClass;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\core\TableException;


class SimpleRuleTest extends CommonTestClass
{
    /**
     * @throws TableException
     */
    public function testAlways(): void
    {
        $lib = new Rules\Always('name');
        $this->assertTrue($lib->validate('name'));
        $this->assertTrue($lib->validate('diff'));
        $this->assertTrue($lib->validate(''));
        $this->assertTrue($lib->validate(false));
        $this->assertTrue($lib->validate(null));
    }

    /**
     * @throws TableException
     */
    public function testExact(): void
    {
        $lib = new Rules\Exact('name');
        $this->assertTrue($lib->validate('name'));
        $this->assertFalse($lib->validate('diff'));
        $this->assertFalse($lib->validate(''));
        $this->assertFalse($lib->validate(null));
        $this->assertFalse($lib->validate(false));
    }

    /**
     * @throws TableException
     */
    public function testEmpty(): void
    {
        $lib = new Rules\REmpty('name');
        $this->assertFalse($lib->validate('name'));
        $this->assertFalse($lib->validate(true));
        $this->assertTrue($lib->validate(''));
        $this->assertTrue($lib->validate(null));
        $this->assertTrue($lib->validate(false));
    }

    /**
     * @throws TableException
     */
    public function testNegate(): void
    {
        $lib = new Rules\Negate(new Rules\REmpty('name'));
        $this->assertTrue($lib->validate('name'));
        $this->assertTrue($lib->validate(true));
        $this->assertFalse($lib->validate(''));
        $this->assertFalse($lib->validate(null));
        $this->assertFalse($lib->validate(false));
    }
}
