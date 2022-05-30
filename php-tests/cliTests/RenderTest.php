<?php

namespace cliTests;


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
use kalanis\kw_table\output_cli\CliRenderer;


class RenderTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testBasics(): void
    {
        $lib = new Table();
        $render = new CliRenderer($lib);
        $lib->setOutput($render);

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| --- | ----- | ------ |' . PHP_EOL
            . '| :id | :name | :title |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '| 1   | abc   | fill   |' . PHP_EOL
            . '| 2   | def   | dude   |' . PHP_EOL
            . '| 3   | ghi   | know   |' . PHP_EOL
            . '| 4   | jkl   | hate   |' . PHP_EOL
            . '| 5   | mno   | call   |' . PHP_EOL
            . '| 6   | pqr   | that   |' . PHP_EOL
            . '| 7   | stu   | life   |' . PHP_EOL
            . '| 8   | vwx   | felt   |' . PHP_EOL
            . '| 9   | yz-   | love   |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            , $lib->render());

        $this->assertNotEmpty($render->getTableEngine());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testOrderedFromPreset(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

        $src = new Sources();
        $src->setAddress('//foo/bar');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| ----- | ------ | ------ |' . PHP_EOL
            . '| *^:id | v:name | :title |' . PHP_EOL
            . '| ----- | ------ | ------ |' . PHP_EOL
            . '| 9     | yz-    | love   |' . PHP_EOL
            . '| 8     | vwx    | felt   |' . PHP_EOL
            . '| 7     | stu    | life   |' . PHP_EOL
            . '| 6     | pqr    | that   |' . PHP_EOL
            . '| 5     | mno    | call   |' . PHP_EOL
            . '| 4     | jkl    | hate   |' . PHP_EOL
            . '| 3     | ghi    | know   |' . PHP_EOL
            . '| 2     | def    | dude   |' . PHP_EOL
            . '| 1     | abc    | fill   |' . PHP_EOL
            . '| ----- | ------ | ------ |' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testOrderedFromLink1(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

        $src = new Sources();
        $src->setAddress('//foo/bar?column=name&direction=ASC');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| ---- | ------- | ------ |' . PHP_EOL
            . '| v:id | *v:name | :title |' . PHP_EOL
            . '| ---- | ------- | ------ |' . PHP_EOL
            . '| 1    | abc     | fill   |' . PHP_EOL
            . '| 2    | def     | dude   |' . PHP_EOL
            . '| 3    | ghi     | know   |' . PHP_EOL
            . '| 4    | jkl     | hate   |' . PHP_EOL
            . '| 5    | mno     | call   |' . PHP_EOL
            . '| 6    | pqr     | that   |' . PHP_EOL
            . '| 7    | stu     | life   |' . PHP_EOL
            . '| 8    | vwx     | felt   |' . PHP_EOL
            . '| 9    | yz-     | love   |' . PHP_EOL
            . '| ---- | ------- | ------ |' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testOrderedFromLink2(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

        $src = new Sources();
        $src->setAddress('//foo/bar?column=name&direction=DESC');
        $lib->addOrder(new Order(new Handler($src)));

        $lib->addOrderedColumn('id', new Columns\Basic('id'));
        $lib->addOrderedColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addOrdering('id', IOrder::ORDER_DESC);
        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| ---- | ------- | ------ |' . PHP_EOL
            . '| v:id | *^:name | :title |' . PHP_EOL
            . '| ---- | ------- | ------ |' . PHP_EOL
            . '| 9    | yz-     | love   |' . PHP_EOL
            . '| 8    | vwx     | felt   |' . PHP_EOL
            . '| 7    | stu     | life   |' . PHP_EOL
            . '| 6    | pqr     | that   |' . PHP_EOL
            . '| 5    | mno     | call   |' . PHP_EOL
            . '| 4    | jkl     | hate   |' . PHP_EOL
            . '| 3    | ghi     | know   |' . PHP_EOL
            . '| 2    | def     | dude   |' . PHP_EOL
            . '| 1    | abc     | fill   |' . PHP_EOL
            . '| ---- | ------- | ------ |' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testFilter(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));
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

        $this->assertEquals(
              '| --- | ------ | ------- |' . PHP_EOL
            . '| :id | >:name | >:title |' . PHP_EOL
            . '| --- | ------ | ------- |' . PHP_EOL
            . '| 9   | yz-    | love    |' . PHP_EOL
            . '| 8   | vwx    | felt    |' . PHP_EOL
            . '| 7   | stu    | life    |' . PHP_EOL
            . '| 4   | jkl    | hate    |' . PHP_EOL
            . '| 2   | def    | dude    |' . PHP_EOL
            . '| --- | ------ | ------- |' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testPager1(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

        // pager
        $pager = new BasicPager();
        $pager->setLimit(3);
        $pager->setActualPage(1);
        $lib->addPager(new CliPager(new Positions($pager)));

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| --- | ----- | ------ |' . PHP_EOL
            . '| :id | :name | :title |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '| 1   | abc   | fill   |' . PHP_EOL
            . '| 2   | def   | dude   |' . PHP_EOL
            . '| 3   | ghi   | know   |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '' . PHP_EOL
            . '-- | - | 1 | 2 > | 3 >>' . PHP_EOL
            . 'Showing results 1 - 3 of total 9' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testPager2(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

        // pager
        $pager = new BasicPager();
        $pager->setLimit(3);
        $pager->setActualPage(2);
        $lib->addPager(new CliPager(new Positions($pager)));

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| --- | ----- | ------ |' . PHP_EOL
            . '| :id | :name | :title |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '| 4   | jkl   | hate   |' . PHP_EOL
            . '| 5   | mno   | call   |' . PHP_EOL
            . '| 6   | pqr   | that   |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '' . PHP_EOL
            . '<< 1 | < 1 | 2 | 3 > | 3 >>' . PHP_EOL
            . 'Showing results 4 - 6 of total 9' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testPager3(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

        // pager
        $pager = new BasicPager();
        $pager->setLimit(3);
        $pager->setActualPage(3);
        $lib->addPager(new CliPager(new Positions($pager)));

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $this->assertEquals(
              '| --- | ----- | ------ |' . PHP_EOL
            . '| :id | :name | :title |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '| 7   | stu   | life   |' . PHP_EOL
            . '| 8   | vwx   | felt   |' . PHP_EOL
            . '| 9   | yz-   | love   |' . PHP_EOL
            . '| --- | ----- | ------ |' . PHP_EOL
            . '' . PHP_EOL
            . '<< 1 | < 2 | 3 | - | --' . PHP_EOL
            . 'Showing results 7 - 9 of total 9' . PHP_EOL
            , $lib->render());
    }

    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testEverything(): void
    {
        $lib = new Table();
        $lib->setOutput(new CliRenderer($lib));

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

        $this->assertEquals(
              '| ----- | ------- | ------- |' . PHP_EOL
            . '| *v:id | v>:name | >:title |' . PHP_EOL
            . '| ----- | ------- | ------- |' . PHP_EOL
            . '| 8     | vwx     | felt    |' . PHP_EOL
            . '| 9     | yz-     | love    |' . PHP_EOL
            . '| ----- | ------- | ------- |' . PHP_EOL
            . '' . PHP_EOL
            . '<< 1 | < 1 | 2 | - | --' . PHP_EOL
            . 'Showing results 4 - 5 of total 5' . PHP_EOL
            , $lib->render());
    }
}
