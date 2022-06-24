<?php

namespace coreTests\Columns;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table\Columns;


class SimpleColumnTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     */
    public function testCore(): void
    {
        $lib = new XColumn('name');
        $this->assertEquals('name', $lib->getSourceName());
        $this->assertEquals('name', $lib->getFilterName());
        $this->assertEquals('', $lib->getHeaderText());
        $lib->setHeaderText(null);
        $this->assertEquals('name', $lib->getHeaderText());
        $lib->setHeaderText('else');
        $this->assertEquals('else', $lib->getHeaderText());
        $this->assertTrue($lib->canOrder());

        $data = $this->getRow();
        $this->assertEquals('def', $lib->getValue($data));
        $this->assertEquals(456, $lib->getOverrideValue($data, 'size'));

        $this->assertEquals('def', $lib->translate($data));

        $this->assertFalse($lib->hasHeaderFilterField());
        $this->assertFalse($lib->hasFooterFilterField());
        $this->assertEmpty($lib->getHeaderFilterField());
        $this->assertEmpty($lib->getFooterFilterField());
        $lib->setHeaderFiltering(new \XField());
        $lib->setFooterFiltering(new \XField());
        $this->assertTrue($lib->hasHeaderFilterField());
        $this->assertTrue($lib->hasFooterFilterField());
    }

    public function testWrapper(): void
    {
        $lib = new XWrapper();
        $lib->addWrapper('li', ['baz' => 'uiy']);
        $this->assertEquals('<li baz="uiy">def</li>', $lib->formattedData('def'));
        $lib->addWrapper('ul', 'foo="bar"');
        $this->assertEquals('<ul foo="bar"><li baz="uiy">def</li></ul>', $lib->formattedData('def'));
    }

    /**
     * @throws ConnectException
     */
    public function testBasicWithConvert(): void
    {
        $lib = new Columns\Basic('desc');
        $data = $this->getRow();
        $this->assertEquals('<lang_to_"convert">', $lib->getValue($data));
        $lib->setEscapeFlags(ENT_IGNORE);
        $this->assertEquals('&lt;lang_to_"convert"&gt;', $lib->getValue($data));
    }

    /**
     * @throws ConnectException
     */
    public function testStaticBasic(): void
    {
        $lib = new Columns\CStatic('to out', '', 'id');
        $this->assertEquals('to out', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testStaticStyle(): void
    {
        $lib = new Columns\CStatic('to out', 'style_me', 'id');
        $this->assertEquals('<span class="style_me">to out</span>', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testBold(): void
    {
        $lib = new Columns\Bold('name');
        $this->assertEquals('<strong>def</strong>', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testEmpty(): void
    {
        $lib = new Columns\CEmpty();
        $this->assertEmpty($lib->getValue($this->getRow()));
        $this->assertFalse($lib->canOrder());
    }

    /**
     * @throws ConnectException
     */
    public function testCurrency(): void
    {
        $lib = new Columns\Currency('size', 'EUR');
        $this->assertEquals('456 EUR', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testEmail(): void
    {
        $lib = new Columns\Email('name');
        $this->assertEquals('<a href="mailto:def">def</a>', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testPreformatted(): void
    {
        $lib = new Columns\Pre('desc');
        $this->assertEquals('<lang_to_"convert">', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testFormat(): void
    {
        $lib = new Columns\Sprintf('name', '>> %s <<');
        $this->assertEquals('>> def <<', $lib->getValue($this->getRow()));
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 2, 'name' => 'def', 'desc' => '<lang_to_"convert">', 'size' => 456, 'enabled' => 0]);
    }
}


class XColumn extends Columns\AColumn
{
    public function __construct($sourceName)
    {
        $this->sourceName = $sourceName;
    }
}


class XWrapper
{
    use Columns\TWrappers;

    public function formattedData($data): string
    {
        return $this->formatData($data);
    }
}
