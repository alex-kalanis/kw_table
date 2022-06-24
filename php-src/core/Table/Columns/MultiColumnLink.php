<?php

namespace kalanis\kw_table\core\Table\Columns;


use kalanis\kw_connect\core\Interfaces\IRow;


/**
 * Class MultiColumnLink
 * @package kalanis\kw_table\core\Table\Columns
 * Process multiple columns
 */
class MultiColumnLink extends AColumn
{
    use TEscapedValue;

    /** @var callable */
    protected $callback;
    /** @var AColumn[] */
    protected $params;

    /**
     * @param string     $sourceName  basic column (for sorting or filtering)
     * @param AColumn[]  $params      another data columns
     * @param callable   $callback    function, which will process that
     */
    public function __construct(string $sourceName, array $params, $callback)
    {
        $this->sourceName = $sourceName;
        $this->callback = $callback;
        $this->params = $params;
    }

    public function getValue(IRow $source)
    {
        $return = [];
        $return[] = parent::getValue($source);
        foreach ($this->params AS $param) {
            $return[] = $param->getValue($source);
        }
        return call_user_func($this->callback, $return);
    }

    public function canOrder(): bool
    {
        return false;
    }
}
