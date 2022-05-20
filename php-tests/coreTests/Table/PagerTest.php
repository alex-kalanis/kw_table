<?php

namespace coreTests\Table;


use CommonTestClass;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources\Sources;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render\CliPager;
use kalanis\kw_table\core\Connector\PageLink;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\TableException;


class PagerTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testNormal(): void
    {
        $lib = new Table();
        $this->assertEmpty($lib->getPager());

        $src = new Sources();
        $src->setAddress('//foo/bar');

        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler($src), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $lib->addPager(new CliPager(new Positions($pager)));

        $lib->addColumn('id', new Columns\Basic('id'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->translateData();
        $this->assertNotEmpty($lib->getPager());
    }
}
