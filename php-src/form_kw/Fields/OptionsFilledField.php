<?php

namespace kalanis\kw_table\form_kw\Fields;


/**
 * Class OptionsFilledField
 * @package kalanis\kw_table\form_kw\Fields
 * Without empty value, just defined ones
 */
class OptionsFilledField extends Options
{
    /**
     * OptionsFilledField constructor.
     * @param array<string, string|int|float> $options
     * @param array<string, string> $attributes
     */
    public function __construct(array $options = [], array $attributes = [])
    {
        parent::__construct([], $attributes);
        $this->setFilledOptions($options);
    }

    /**
     * @param array<string, string|int|float> $options
     * @return $this
     */
    public function setFilledOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }
}
