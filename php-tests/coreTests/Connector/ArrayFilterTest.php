<?php

namespace coreTests\Connector;


use CommonTestClass;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_table\core\Connector\ArrayFilterForm;


class ArrayFilterTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws RenderException
     */
    public function testSimple(): void
    {
        $lib = new ArrayFilterForm([
            'abc' => 'def',
            'ghi' => 'jkl',
            'mno' => 'pqr',
        ]);

        $lib->addField(new \XField());

        $this->assertEquals('jkl', $lib->getValue('ghi'));
        $this->assertEquals(null, $lib->getValue('stu'));

        $lib->setValue('stu', 'vwx');
        $this->assertEquals('vwx', $lib->getValue('stu'));

        $this->assertEquals([
            'abc' => 'def',
            'ghi' => 'jkl',
            'mno' => 'pqr',
            'stu' => 'vwx',
        ], $lib->getValues());

        $this->assertEmpty($lib->getFormName());
        $this->assertEmpty($lib->renderStart());
        $this->assertEmpty($lib->renderEnd());
        $this->assertEmpty($lib->renderField('none here'));
    }
}
