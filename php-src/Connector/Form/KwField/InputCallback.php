<?php

namespace kalanis\kw_table\Connector\Form\KwField;


use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Interfaces\Connector\IFilterType;
use kalanis\kw_table\Interfaces\Table\IFilterRender;


/**
 * Class InputCallback
 * @package kalanis\kw_table\Connector\Form\KwField
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

    public function getFilterType(): IFilterType
    {
        return Factory::getInstance()->getFilter($this->dataSource->getFilterType(), 'exact');
    }

    public function add(): void
    {
    }

    public function renderContent(): string
    {
        return call_user_func($this->callback, $this->attributes);
    }
}
