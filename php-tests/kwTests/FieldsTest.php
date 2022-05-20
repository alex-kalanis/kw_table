<?php

namespace kwTests;


use CommonTestClass;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_forms\Adapters\ArrayAdapter;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_forms\Form;
use kalanis\kw_table\core\TableException;
use kalanis\kw_table\form_kw\Fields;


class FieldsTest extends CommonTestClass
{
    public function testExactBasic(): void
    {
        $form = new Form('testing');
        $lib = new Fields\TextExact();
        $this->assertEquals(IFilterFactory::ACTION_EXACT, $lib->getFilterAction());
        $lib->setDataSourceConnector(new Connector($this->basicData()));

        $this->assertEmpty($lib->getForm());
        $lib->setForm($form);
        $this->assertEquals($form, $lib->getForm());

        $this->assertEquals('', $lib->getAlias());
        $lib->setAlias('test');
        $this->assertEquals('test', $lib->getAlias());

        $lib->setAttributes(['foo' => 'bar', 'baz' => 'fuu']);
        $lib->addAttribute('uhu', 'huh');
        $lib->add();
    }

    public function testContains(): void
    {
        $form = new Form('testing');
        $lib = new Fields\TextContains();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_CONTAINS, $lib->getFilterAction());
        $lib->add();
    }

    public function testOptions(): void
    {
        $form = new Form('testing');
        $lib = new Fields\Options();
        $lib->setAlias('test');
        $lib->setForm($form);
        $lib->setEmptyItem('nope');
        $lib->setOptions(['abc', 'def', 'ghi']);
        $this->assertEquals(IFilterFactory::ACTION_EXACT, $lib->getFilterAction());
        $lib->add();
    }

    public function testOptionsFilled(): void
    {
        $form = new Form('testing');
        $lib = new Fields\OptionsFilledField(['abc', 'def', 'ghi']);
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_EXACT, $lib->getFilterAction());
        $lib->add();
    }

    public function testNumToW(): void
    {
        $form = new Form('testing');
        $lib = new Fields\NumToWith();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_TO_WITH, $lib->getFilterAction());
        $lib->add();
    }

    public function testNumTo(): void
    {
        $form = new Form('testing');
        $lib = new Fields\NumTo();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_TO, $lib->getFilterAction());
        $lib->add();
    }

    public function testNumFromW(): void
    {
        $form = new Form('testing');
        $lib = new Fields\NumFromWith();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_FROM_WITH, $lib->getFilterAction());
        $lib->add();
    }

    public function testNumFrom(): void
    {
        $form = new Form('testing');
        $lib = new Fields\NumFrom();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_FROM, $lib->getFilterAction());
        $lib->add();
    }

    public function testMultiSelect(): void
    {
        $form = new Form('testing');
        $lib = new Fields\MultiSelect('foo');
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_EXACT, $lib->getFilterAction());
        $lib->add();
    }

    public function testCallback(): void
    {
        $form = new Form('testing');
        $lib = new Fields\InputCallback([$this, 'callbackOut']);
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_EXACT, $lib->getFilterAction());
        $lib->add();
        $this->assertEquals('prepared', $lib->renderContent());
    }

    public function callbackOut(): string
    {
        return 'prepared';
    }

    public function testRange(): void
    {
        $form = new Form('testing');
        $lib = new Fields\DateRange();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_RANGE, $lib->getFilterAction());
        $lib->add();
    }

    public function testPicker(): void
    {
        $form = new Form('testing');
        $lib = new Fields\DateTimePicker();
        $lib->setAlias('test');
        $lib->setForm($form);
        $this->assertEquals(IFilterFactory::ACTION_EXACT, $lib->getFilterAction());
        $lib->add();
    }

    /**
     * @throws TableException
     * @throws FormsException
     */
    public function testMultiple(): void
    {
        $form = new Form('testing');
        $form->setMethod('GET');
        $form->setInputs(new ArrayAdapter([
            'test_0' => 232,
            'test_1' => 642,
        ]));

        $lib = new Fields\Multiple([
            new Fields\MultipleValue(new Fields\NumFrom(), 'From'),
            new Fields\MultipleValue(new Fields\NumToWith(), 'To')
        ]);
        $lib->setAlias('test');
        $lib->setForm($form);
        $lib->setDataSourceConnector(new Connector($this->basicData()));
        $this->assertEquals(IFilterFactory::ACTION_MULTIPLE, $lib->getFilterAction());
        $lib->add();

        $form->process();
        $lib->renderContent();
        $this->assertEquals([
            [IFilterFactory::ACTION_FROM, 232],
            [IFilterFactory::ACTION_TO_WITH, 642],
        ], $lib->getPairs());
    }

    /**
     * @throws TableException
     * @throws FormsException
     */
    public function testMultipleDifferentAliases(): void
    {
        $form = new Form('testing');
        $form->setMethod('GET');
        $form->setInputs(new ArrayAdapter([
            'test_anon' => 232,
            'fillin' => 642,
        ]));

        $lib = new Fields\Multiple([
            new Fields\MultipleValue(new Fields\NumFrom(), 'From'),
            new Fields\MultipleValue(new Fields\NumToWith(), 'To', 'fillin')
        ]);
        $lib->setForm($form);
        $lib->setDataSourceConnector(new Connector($this->basicData()));
        $this->assertEquals(IFilterFactory::ACTION_MULTIPLE, $lib->getFilterAction());
        $lib->add();

        $form->process();
        $lib->renderContent();
        $this->assertEquals([
            [IFilterFactory::ACTION_TO_WITH, 642],
        ], $lib->getPairs());
    }

    /**
     * @throws TableException
     */
    public function testMultipleFailField(): void
    {
        $this->expectException(TableException::class);
        new Fields\Multiple([
            new Fields\NumFrom(),
        ]);
    }
}
