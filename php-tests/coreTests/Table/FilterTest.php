<?php

namespace coreTests\Table;


use CommonTestClass;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Connector\ArrayFilterForm;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\TableException;


class FilterTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNormal(): void
    {
        $lib = new Table();

        $this->assertEmpty($lib->getHeaderFilter());
        $this->assertEmpty($lib->getFooterFilter());
        $form = new ArrayFilterForm([
            'name' => 'e',
        ]);
        $lib->addHeaderFilter($form);
        $lib->addFooterFilter($form);
        $lib->setDefaultHeaderFilterFieldAttributes(['xyz' => 'fsy']);
        $lib->setDefaultFooterFilterFieldAttributes(['wyxf' => 'vbsd']);

        $lib->addColumn('id', new Columns\Basic('id'), new \XField(), new \XRenderField('out id'));
        $lib->addColumn('name', new Columns\Basic('name'), new \XField(), new \XRenderField('out name'));
        $lib->addColumn('title', new Columns\Basic('desc'), new \XField(), new \XRenderField('out title'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->translateData();
        $this->assertNotEmpty($lib->getHeaderFilter());
        $this->assertNotEmpty($lib->getFooterFilter());
        $this->assertEmpty($lib->getFormName()); // ArrayFilterForm has no form name
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNoFilterForm(): void
    {
        $lib = new Table();

        $lib->addColumn('id', new Columns\Basic('id'), new \XField(), new \XRenderField('out id'));
        $lib->addColumn('name', new Columns\Basic('name'), new \XField(), new \XRenderField('out name'));
        $lib->addColumn('title', new Columns\Basic('desc'), new \XField(), new \XRenderField('out title'));

        $this->assertEmpty($lib->getHeaderFilter());
        $this->assertEmpty($lib->getFooterFilter());
        $this->assertEmpty($lib->getFormName());
    }
}
