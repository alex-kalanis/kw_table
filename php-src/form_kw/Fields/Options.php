<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_connect\core\Interfaces\IFilterType;


/**
 * Class Options
 * @package kalanis\kw_table\form_kw\Fields
 * Contains empty value
 */
class Options extends AField
{
    /** @var string */
    protected $emptyItem = '- all -';
    /** @var array<string, string|int|float> */
    protected $options = [];

    /**
     * @param array<string, string|int|float> $options
     * @param array<string, string> $attributes
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
     * @param array<string, string|int|float> $options
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
        $this->form->/** @scrutinizer ignore-call */addSelect($this->alias, '', null, $this->options, $this->attributes);
    }
}
