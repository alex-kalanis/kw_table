<?php

namespace jsonTests;


use CommonTestClass;
use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources\Sources;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IOrder;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render\CliPager;
use kalanis\kw_table\core\Connector\ArrayFilterForm;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Order;
use kalanis\kw_table\core\TableException;
use kalanis\kw_table\form_kw\Fields\TextContains;
use kalanis\kw_table\output_json\JsonRenderer;


class RenderTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testBasics(): void
    {
        $lib = new Table();

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":[],"filtered":[],"body":[{"id":1,"name":"abc","desc":"fill"},{"id":2,"name":"def","desc":"dude"},{"id":3,"name":"ghi","desc":"know"},{"id":4,"name":"jkl","desc":"hate"},{"id":5,"name":"mno","desc":"call"},{"id":6,"name":"pqr","desc":"that"},{"id":7,"name":"stu","desc":"life"},{"id":8,"name":"vwx","desc":"felt"},{"id":9,"name":"yz-","desc":"love"}],"pager":[]}'
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testOrderedFromPreset(): void
    {
        $lib = new Table();

        $src = new Sources();
        $src->setAddress('//foo/bar');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":{"id":{"is_active":1,"direction":"ASC"},"name":{"is_active":0,"direction":"ASC"}},"filtered":[],"body":[{"id":9,"name":"yz-","desc":"love"},{"id":8,"name":"vwx","desc":"felt"},{"id":7,"name":"stu","desc":"life"},{"id":6,"name":"pqr","desc":"that"},{"id":5,"name":"mno","desc":"call"},{"id":4,"name":"jkl","desc":"hate"},{"id":3,"name":"ghi","desc":"know"},{"id":2,"name":"def","desc":"dude"},{"id":1,"name":"abc","desc":"fill"}],"pager":[]}'
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testOrderedFromLink1(): void
    {
        $lib = new Table();

        $src = new Sources();
        $src->setAddress('//foo/bar?column=name&direction=ASC');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":{"id":{"is_active":0,"direction":"ASC"},"name":{"is_active":1,"direction":"DESC"}},"filtered":[],"body":[{"id":1,"name":"abc","desc":"fill"},{"id":2,"name":"def","desc":"dude"},{"id":3,"name":"ghi","desc":"know"},{"id":4,"name":"jkl","desc":"hate"},{"id":5,"name":"mno","desc":"call"},{"id":6,"name":"pqr","desc":"that"},{"id":7,"name":"stu","desc":"life"},{"id":8,"name":"vwx","desc":"felt"},{"id":9,"name":"yz-","desc":"love"}],"pager":[]}'
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testOrderedFromLink2(): void
    {
        $lib = new Table();

        $src = new Sources();
        $src->setAddress('//foo/bar?column=name&direction=DESC');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":{"id":{"is_active":0,"direction":"ASC"},"name":{"is_active":1,"direction":"ASC"}},"filtered":[],"body":[{"id":9,"name":"yz-","desc":"love"},{"id":8,"name":"vwx","desc":"felt"},{"id":7,"name":"stu","desc":"life"},{"id":6,"name":"pqr","desc":"that"},{"id":5,"name":"mno","desc":"call"},{"id":4,"name":"jkl","desc":"hate"},{"id":3,"name":"ghi","desc":"know"},{"id":2,"name":"def","desc":"dude"},{"id":1,"name":"abc","desc":"fill"}],"pager":[]}'
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testFilter(): void
    {
        $lib = new Table();
        $lib->addHeaderFilter(new ArrayFilterForm([
            'desc' => 'e',
        ]));

        $src = new Sources();
        $src->setAddress('//foo/bar');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'), new TextContains());
        $lib->addColumn('title', new Columns\Basic('desc'), new TextContains());

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":[],"filtered":{"name":null,"desc":"e"},"body":[{"id":9,"name":"yz-","desc":"love"},{"id":8,"name":"vwx","desc":"felt"},{"id":7,"name":"stu","desc":"life"},{"id":4,"name":"jkl","desc":"hate"},{"id":2,"name":"def","desc":"dude"}],"pager":[]}'
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testPager1(): void
    {
        $lib = new Table();

        // pager
        $pager = new BasicPager();
        $pager->setLimit(3);
        $pager->setActualPage(1);
        $lib->addPager(new CliPager(new Positions($pager)));

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":[],"filtered":[],"body":[{"id":1,"name":"abc","desc":"fill"},{"id":2,"name":"def","desc":"dude"},{"id":3,"name":"ghi","desc":"know"}],"pager":{"positions":{"first":1,"prev":1,"actual":1,"next":2,"last":3},"results":{"from":1,"to":3,"total":9}}}'
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testEverything(): void
    {
        $lib = new Table();

        // filter
        $lib->addHeaderFilter(new ArrayFilterForm([
            'desc' => 'e',
        ]));

        // order
        $src = new Sources();
        $src->setAddress('//foo/bar');
        $lib->addOrder(new Order(new Handler($src)));

        // pager
        $pager = new BasicPager();
        $pager->setLimit(3);
        $pager->setActualPage(2);
        $lib->addPager(new CliPager(new Positions($pager)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'), new TextContains());
        $lib->addColumn('title', new Columns\Basic('desc'), new TextContains());

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->setOutput(new JsonRenderer($lib));
        $this->assertEquals(
            '{"header":{"id":"id","name":"name","desc":"title"},"sorted":{"id":{"is_active":1,"direction":"DESC"},"name":{"is_active":0,"direction":"ASC"}},"filtered":{"name":null,"desc":"e"},"body":[{"id":8,"name":"vwx","desc":"felt"},{"id":9,"name":"yz-","desc":"love"}],"pager":{"positions":{"first":1,"prev":1,"actual":2,"next":2,"last":2},"results":{"from":4,"to":5,"total":5}}}'
            , $lib->render());
    }
}
