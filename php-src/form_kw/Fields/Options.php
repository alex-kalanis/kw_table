<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_connect\core\Interfaces\IFilterType;


/**
 * Class Options
 * @package kalanis\kw_table\form_kw\Fields
 */
class Options extends AField
{
    /** @var string */
    protected $emptyItem = '- all -';
    /** @var string[]|int[] */
    protected $options = [];

    /**
     * @param string[] $options
     * @param string[] $attributes
     */
    public function __construct(array $options = [], array $attributes = [])
    {
        $this->setOptions($options);
        parent::__construct($attributes);
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    /**
     * @param string[]|int[] $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = [IFilterType::EMPTY_FILTER => $this->emptyItem] + $options;
        return $this;
    }

    public function setEmptyItem(string $text): void
    {
        $this->emptyItem = $text;
    }

    public function add(): void
    {
        $this->form->addSelect($this->alias, '', null, $this->options, $this->attributes);
    }
}
