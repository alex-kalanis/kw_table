<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_table\core\Interfaces\Table\IFilterRender;


/**
 * Class InputCallback
 * @package kalanis\kw_table\form_kw\Fields
 * Also - put inside filter what you want
 */
class InputCallback extends AField implements IFilterRender
{
    protected $callback = null;

    public function __construct(callable $callback, array $attributes = [])
    {
        $this->setCallback($callback);
        parent::__construct($attributes);
    }

    public function setCallback(callable $callback): void
    {
        $this->callback = $callback;
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    public function add(): void
    {
    }

    public function renderContent(): string
    {
        return call_user_func($this->callback, $this->attributes);
    }
}
