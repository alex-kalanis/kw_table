<?php

namespace kalanis\kw_table\Table\Columns;


use kalanis\kw_table\Interfaces\Table\IRow;


/**
 * Class MultiColumnLink
 * @package kalanis\kw_table\Table\Columns
 * Process multiple columns
 */
class MultiColumnLink extends AColumn
{
    use EscapedValueTrait;

    /** @var callable */
    protected $callback;
    /** @var AColumn[] */
    protected $params;

    /**
     * @param string     $sourceName  basic column (for sorting or filtering)
     * @param AColumn[]  $params      another data columns
     * @param callable   $callback    function, which will process that
     */
    public function __construct(string $sourceName, array $params, callable $callback)
    {
        $this->sourceName = $sourceName;
        $this->callback = $callback;
        $this->params = $params;
    }

    public function getValue(IRow $source)
    {
        $return[] = parent::getValue($source);
        foreach ($this->params AS $param) {
            $return[] = $param->getValue($source);
        }
        return call_user_func($this->callback, $return);
    }
}
