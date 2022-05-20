<?php

namespace coreTests\Table;


use CommonTestClass;
use kalanis\kw_connect\arrays\Connector;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\TableException;


class RowsTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     * @throws TableException
     */
    public function testFunc(): void
    {
        $lib = new Table();

        // basic way via call
        // add class "shady" when cell "name" contains "mno"
        $lib->rowClass('shady', new Table\Rules\Exact('mno'), 'name');

        // another way via magical methods
        $lib->rowId('white'); // call function name... - it ends in Styles
        $lib->rowName('dark', new Table\Rules\Exact('pqr'));

        $lib->addColumn('id', new Columns\Basic('id'));
        $lib->addColumn('name', new Columns\Basic('name'));
        $lib->addColumn('title', new Columns\Basic('desc'));

        $lib->addDataSetConnector(new Connector($this->basicData()));

        $lib->translateData();
        // just try something!
        $this->assertEquals(9, $lib->rowCount());
        $this->assertEquals(3, $lib->colCount());
    }
}
