<?php

namespace coreTests\Columns;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table\Columns;


class DeepColumnTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     */
    public function testMultiSelect(): void
    {
        $lib = new Columns\MultiSelectCheckbox('id');
        $this->assertEquals('<input type="checkbox" name="multiselect[2]" class="multiselect">', $lib->getValue($this->getRow()));
        $this->assertFalse($lib->canOrder());
    }

    /**
     * @throws ConnectException
     */
    public function testRowDataFormatted(): void
    {
        $lib = new Columns\RowData(['name', 'size'], [$this, 'mergeSize']);
        $this->assertEquals('def::456', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testMulti(): void
    {
        $lib = new Columns\Multi('!!!', 'id');
        $lib->addColumn(new Columns\Bold('name'));
        $lib->addColumn(new Columns\Currency('size', 'EUR'));
        $this->assertEquals('<strong>def</strong>!!!456 EUR', $lib->getValue($this->getRow()));
        $this->assertFalse($lib->canOrder());
    }

    /**
     * @throws ConnectException
     */
    public function testMultiLink(): void
    {
        $lib = new Columns\MultiColumnLink('id', [
            new Columns\Bold('name'),
            new Columns\Currency('size', 'EUR'),
        ], [$this, 'mergeSize']);
        $this->assertEquals('2::<strong>def</strong>::456 EUR', $lib->getValue($this->getRow()));
        $this->assertFalse($lib->canOrder());
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 2, 'name' => 'def', 'desc' => '<lang_to_"convert">', 'size' => 456, 'enabled' => 0]);
    }

    public function mergeSize($params): string
    {
        return implode('::', $params);
    }
}
