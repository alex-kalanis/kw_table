<?php

namespace kalanis\kw_table\form_nette\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_connect\core\Interfaces\IFilterType;


/**
 * Class Options
 * @package kalanis\kw_table\form_nette\Fields
 */
class Options extends AField
{
    /** @var string */
    protected $emptyItem;

    /** @var string[] */
    protected $options = [];

    /** @var int|null */
    protected $size = null;

    public function __construct(array $options = [], array $attributes = [], string $emptyItem = '- all -')
    {
        $this->setEmptyItem($emptyItem);
        $this->setOptions($options);
        if (isset($attributes[static::ATTR_SIZE])) {
            $this->size = $attributes[static::ATTR_SIZE];
            unset($attributes[static::ATTR_SIZE]);
        }
        parent::__construct($attributes);
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_EXACT;
    }

    /**
     * @param string[] $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = [IFilterType::EMPTY_FILTER => $this->emptyItem] + $options;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setEmptyItem($text)
    {
        $this->emptyItem = $text;
        return $this;
    }

    public function add(): void
    {
        $this->form->addSelect($this->alias, null, $this->options, $this->size);
        $this->processAttributes();
    }
}
