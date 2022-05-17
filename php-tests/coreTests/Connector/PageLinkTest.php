<?php

namespace coreTests\Connector;


use CommonTestClass;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources\Address;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_table\core\Connector\PageLink;


class PageLinkTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $pager = new BasicPager();
        $lib = new PageLink(
            new Handler(new Address('/dummy')),
            $pager->setMaxResults(30)->setLimit(10),
            'pg'
        );

        // at first
        $this->assertEquals('/dummy?pg=1', $lib->getPageLink());

        $lib->setPageNumber(2); // in limit
        $this->assertEquals(2, $lib->getPageNumber());
        $this->assertEquals('/dummy?pg=2', $lib->getPageLink());

        $lib->setPageNumber(5); // over limit
        $this->assertEquals(3, $lib->getPageNumber());
    }

    public function testWithExisting(): void
    {
        $pager = new BasicPager();
        $lib = new PageLink(
            new Handler(new Address('/dummy?xf=uhb&pg=10&vr=nnn')),
            $pager->setMaxResults(30)->setLimit(10),
            'pg'
        );

        // at first
        $this->assertEquals('/dummy?xf=uhb&pg=10&vr=nnn', $lib->getPageLink());

        $lib->setPageNumber(2); // in limit
        $this->assertEquals(2, $lib->getPageNumber());
        $this->assertEquals('/dummy?xf=uhb&pg=2&vr=nnn', $lib->getPageLink());
    }
}
