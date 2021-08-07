<?php

namespace kalanis\kw_table\Connector\Form\KwField;


use kalanis\kw_table\Connector\Filter\Factory;
use kalanis\kw_table\Interfaces\Connector\IFilterType;


/**
 * Class Options
 * @package kalanis\kw_table\Connector\Form\Field
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

    public function getFilterType(): IFilterType
    {
        return Factory::getInstance()->getFilter($this->dataSource->getFilterType(), 'exact');
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
