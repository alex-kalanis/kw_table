<?php

namespace kalanis\kw_table\form_kw\Fields;


/**
 * Class OptionsFilledField
 * @package kalanis\kw_table\form_kw\Fields
 */
class OptionsFilledField extends Options
{
    public function __construct(array $options = [], array $attributes = [])
    {
        parent::__construct([], $attributes);
        $this->setFilledOptions($options);
    }

    public function setFilledOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }
}
