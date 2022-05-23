<?php

namespace kwTests;


use CommonTestClass;
use kalanis\kw_forms\Adapters\ArrayAdapter;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_forms\Form;
use kalanis\kw_table\core\TableException;
use kalanis\kw_table\form_kw\Fields;
use kalanis\kw_table\form_kw\KwFilter;


class FilterTest extends CommonTestClass
{
    /**
     * @throws FormsException
     * @throws RenderException
     * @throws TableException
     */
    public function testExactBasic(): void
    {
        $form = new Form('testing');
        $form->setInputs(new ArrayAdapter([
            'applyTesting' => 'yep', // is it sent
            'bar' => 'huh',
        ]));
        $lib = new KwFilter($form);
        $field1 = new Fields\TextExact();
        $field1->setAlias('foo');
        $field2 = new Fields\TextContains();
        $field2->setAlias('bar');
        $lib->addField($field1);
        $lib->addField($field2);
        $lib->setValue('foo', 'baz');

        $this->assertEquals('testing', $lib->getFormName());
        $this->assertEquals('<form  name="testing" method="get"><input type="hidden" value="apply" name="applyTesting" />' . "\n", $lib->renderStart());
        $this->assertEquals('<input type="text" value="baz" id="foo" name="foo" />', $lib->renderField('foo'));
        $this->assertEquals('<input type="text" value="" id="bar" name="bar" />', $lib->renderField('bar'));
        $this->assertEquals('', $lib->renderField('baz'));
        $this->assertEquals('</form>', $lib->renderEnd());

        $this->assertEquals([
            'applyTesting' => 'yep',
            'foo' => 'baz',
            'bar' => 'huh',
        ], $lib->getValues());
        $this->assertEquals('baz', $lib->getValue('foo'));
        $this->assertEquals('huh', $lib->getValue('bar'));
    }

    /**
     * @throws FormsException
     * @throws TableException
     */
    public function testBadValue(): void
    {
        $form = new Form('testing');
        $form->setInputs(new ArrayAdapter([
            // is it NOT sent
        ]));
        $lib = new KwFilter($form);
        $field1 = new Fields\TextExact();
        $field1->setAlias('foo');
        $lib->addField($field1);
        $lib->setValue('foo', 'baz');

        $this->assertEquals([
            'applyTesting' => '',
            'foo' => 'baz',
        ], $lib->getValues());
        $this->assertEquals(null, $lib->getValue('foo'));
    }

    /**
     * @throws TableException
     */
    public function testBadField(): void
    {
        $form = new Form('testing');
        $lib = new KwFilter($form);
        $this->expectException(TableException::class);
        $lib->addField(new \XField());
    }
}
