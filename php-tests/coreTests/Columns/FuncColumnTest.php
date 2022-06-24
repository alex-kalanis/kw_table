<?php

namespace coreTests\Columns;


use CommonTestClass;
use kalanis\kw_connect\arrays\Row;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_table\core\Table\Columns;


class FuncColumnTest extends CommonTestClass
{
    /**
     * @throws ConnectException
     */
    public function testFunc(): void
    {
        $lib = new Columns\Func('name', [$this, 'columnCallback'], ['x' => 'ytzz']);
        $this->assertEquals('>==> defytzz', $lib->getValue($this->getRow()));
    }

    /**
     * @throws ConnectException
     */
    public function testEscFunc(): void
    {
        $lib = new Columns\EscFunc('name', [$this, 'columnCallback']);
        $this->assertEquals('>==> def', $lib->getValue($this->getRow()));
    }

    protected function getRow(): Row
    {
        return new Row(['id' => 2, 'name' => 'def', 'desc' => '<lang_to_"convert">', 'size' => 456, 'enabled' => 0]);
    }

    public function columnCallback(...$params): string
    {
        $first = reset($params);
        $next = next($params);
        $next = (false !== $next) ? $next : '';
        return '>==> ' . strval($first) . strval($next);
    }
}
