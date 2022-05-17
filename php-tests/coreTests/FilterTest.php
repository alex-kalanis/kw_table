<?php

namespace coreTests;


use CommonTestClass;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_table\core\Connector\ArrayFilterForm;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Filter;
use kalanis\kw_table\core\TableException;


class FilterTest extends CommonTestClass
{
    /**
     * @throws RenderException
     * @throws TableException
     */
    public function testBasics(): void
    {
        $form = new ArrayFilterForm([
            'foo' => 'abc',
            'bar' => 'def',
            'baz' => 'ghi',
        ]);
        $lib = new Filter($form);
        // no output from this filter form
        $this->assertEmpty($lib->getFormName());
        $this->assertEmpty($lib->renderStart());
        $this->assertEmpty($lib->renderEnd());
        $this->assertEquals($form, $lib->getConnector());

        $col1 = new Columns\Basic('foo');
        $col1->setHeaderText('top');
        $col2 = new Columns\Basic('baz');
        $col2->setHeaderText('bottom');
        // no field inside the column - run
        $lib->addHeaderColumn($col1);
        $lib->addFooterColumn($col2);

        $field1 = new \XField();
        $field2 = new \XField();
        // column has field
        $col1->setHeaderFiltering($field1);
        $col2->setFooterFiltering($field2);
        $lib->addHeaderColumn($col1);
        $lib->addFooterColumn($col2);

        // render - here just for call
        $this->assertEquals('', $lib->renderHeaderInput($col1));
        $this->assertEquals('', $lib->renderFooterInput($col2));
    }

    /**
     * @throws RenderException
     * @throws TableException
     */
    public function testBasicsRender(): void
    {
        $lib = new Filter(new ArrayFilterForm([
            'foo' => 'abc',
            'bar' => 'def',
            'baz' => 'ghi',
        ]));
        $this->assertEmpty($lib->getFormName());
        $this->assertEmpty($lib->renderStart());
        $this->assertEmpty($lib->renderEnd());

        $field1 = new \XRenderField('upper');
        $field2 = new \XRenderField('lower');
        $col1 = new Columns\Basic('foo');
        $col1->setHeaderText('top');
        $col1->setHeaderFiltering($field1);
        $col2 = new Columns\Basic('baz');
        $col2->setHeaderText('bottom');
        $col2->setFooterFiltering($field2);

        $lib->addHeaderColumn($col1);
        $lib->addFooterColumn($col2);

        // render
        $this->assertEquals('upper', $lib->renderHeaderInput($col1));
        $this->assertEquals('lower', $lib->renderFooterInput($col2));
    }

    /**
     * @throws RenderException
     * @throws TableException
     */
    public function testUnfilteredHeaderFail(): void
    {
        $lib = new Filter(new ArrayFilterForm([
            'foo' => 'abc',
            'bar' => 'def',
            'baz' => 'ghi',
        ]));

        $col = new Columns\Basic('foo');
        $col->setHeaderText('top');
        $lib->addHeaderColumn($col);
        $col->setHeaderFiltering(new \XField());

        // render
        $this->expectException(TableException::class);
        $lib->renderHeaderInput($col);
    }

    /**
     * @throws RenderException
     * @throws TableException
     */
    public function testUnfilteredFooterFail(): void
    {
        $lib = new Filter(new ArrayFilterForm([
            'foo' => 'abc',
            'bar' => 'def',
            'baz' => 'ghi',
        ]));

        $col = new Columns\Basic('foo');
        $lib->addFooterColumn($col);
        $col->setHeaderFiltering(new \XField());

        // render
        $this->expectException(TableException::class);
        $lib->renderFooterInput($col);
    }

    /**
     * @throws TableException
     */
    public function testSimpleProcessing(): void
    {
        $lib = new Filter(new ArrayFilterForm([
            'foo' => 'abc',
            'bar' => 'def',
            'baz' => 'ghi',
        ]));

        $col1 = new Columns\Basic('foo');
        $col1->setHeaderText('top');
        $col2 = new Columns\Basic('baz');
        $col2->setHeaderText('bottom');
        $col3 = new Columns\Basic('uhb'); // not set
        $col3->setHeaderText('some');
        // no field inside the column
        $lib->addHeaderColumn($col1);
        $lib->addFooterColumn($col2);
        $lib->addHeaderColumn($col3);

        $field1 = new \XField();
        $field2 = new \XField();
        $field3 = new \XField();
        // column has field
        $col1->setHeaderFiltering($field1);
        $col2->setFooterFiltering($field2);
        $col3->setHeaderFiltering($field3);
        $lib->addHeaderColumn($col1);
        $lib->addFooterColumn($col2);
        $lib->addHeaderColumn($col3);

        // values
        $this->assertEmpty($lib->getValue($col1));
        $this->assertEmpty($lib->getValue($col2));
        $this->assertEmpty($lib->getValue($col3));

        $lib->process();
        $this->assertEquals('abc', $lib->getValue($col1));
        $this->assertEquals('ghi', $lib->getValue($col2));
        $this->assertEquals(null, $lib->getValue($col3));
    }

    /**
     * @throws TableException
     */
    public function testMultiProcessing(): void
    {
        $lib = new Filter(new ArrayFilterForm([
            'foo' => 'abc',
            'bar' => ['def', 'jkl', 'mno'],
            'baz' => 'ghi',
            'udh' => 'pqr',
        ]));

        $col1 = new Columns\Basic('foo');
        $col1->setHeaderText('top');
        $col2 = new Columns\Basic('bar');
        $col2->setHeaderText('next one');
        $col3 = new Columns\Basic('baz');
        $col3->setHeaderText('some');
        $col4 = new Columns\Basic('udh');
        $col4->setHeaderText('rest');

        $field1 = new \XRenderMultiField('zyx', ['s', 'h', ]); // value here
        $field2 = new \XRenderMultiField('wvu', ['f', ]); // value here
        $field3 = new \XField(); // value from form
        $field4 = new \XRenderMultiField('tsr', ['values', 'more']); // value here
        $field5 = new \XField(); // value from form
        $field6 = new \XField(); // value from form

        // set header filters
        $col1->setHeaderFiltering($field1);
        $col2->setHeaderFiltering($field2);
        $col3->setHeaderFiltering($field3);
        $col4->setHeaderFiltering($field5);
        // set footer filters
        $col1->setFooterFiltering($field1);
        $col2->setFooterFiltering($field2);
        $col3->setFooterFiltering($field4);
        $col4->setFooterFiltering($field6);
        // add headers
        $lib->addHeaderColumn($col1);
        $lib->addHeaderColumn($col2);
        $lib->addHeaderColumn($col3);
        $lib->addHeaderColumn($col4);
        // add footers
        $lib->addFooterColumn($col1);
        $lib->addFooterColumn($col2);
        $lib->addFooterColumn($col3);
        $lib->addFooterColumn($col4);

        // check initial values
        $this->assertEmpty($lib->getValue($col1));
        $this->assertEmpty($lib->getValue($col2));
        $this->assertEmpty($lib->getValue($col3));
        $this->assertEmpty($lib->getValue($col4));

        // process it
        $lib->process();

        // results of combinations
        $this->assertEquals(['s', 'h', 's', 'h', ], $lib->getValue($col1)); // because it's set twice - top and bottom
        $this->assertEquals(['f', 'f', ], $lib->getValue($col2));
        $this->assertEquals(['ghi', 'values', 'more'], $lib->getValue($col3)); // here it can be seen
        $this->assertEquals(['pqr', 'pqr'], $lib->getValue($col4));
    }
}
