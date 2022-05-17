<?php

namespace coreTests\Rules;


use CommonTestClass;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\core\TableException;


class EvalRuleTest extends CommonTestClass
{
    /**
     * @throws TableException
     */
    public function testMatch(): void
    {
        $lib = new Rules\REval('<3');
        $this->assertTrue($lib->validate('2'));
        $this->assertFalse($lib->validate('name'));
        $this->assertFalse($lib->validate('8'));

        $lib = new Rules\REval('> 5');
        $this->assertTrue($lib->validate(10));

        $lib = new Rules\REval('!= 10');
        $this->assertFalse($lib->validate(10));
        $this->assertTrue($lib->validate(7));

        $lib = new Rules\REval('= cosy');
        $this->assertFalse($lib->validate('wat'));
        $this->assertFalse($lib->validate(2));
        $this->assertTrue($lib->validate('cosy'));

        $lib = new Rules\REval('<= 5');
        $this->assertTrue($lib->validate(4));
        $this->assertTrue($lib->validate(5));
        $this->assertFalse($lib->validate(6));

        $lib = new Rules\REval('>= 5');
        $this->assertFalse($lib->validate(4));
        $this->assertTrue($lib->validate(5));
        $this->assertTrue($lib->validate(6));

        $lib = new Rules\REval('== 5');
        $this->assertTrue($lib->validate('5'));
        $this->assertFalse($lib->validate(5));
    }

    /**
     * @throws TableException
     */
    public function testBadMatch(): void
    {
        $lib = new Rules\REval('<== 3');
        $this->expectException(TableException::class);
        $this->expectExceptionMessage('Unrecognized expression sign');
        $lib->validate('10');
    }

    /**
     * @throws TableException
     */
    public function testBadQuery(): void
    {
        $lib = new Rules\REval('call3');
        $this->expectException(TableException::class);
        $this->expectExceptionMessage('Unrecognized expression pattern');
        $lib->validate('10');
    }
}
