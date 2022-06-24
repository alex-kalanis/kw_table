<?php

namespace kalanis\kw_table\core\Table\Columns;


/**
 * Trait TWrappers
 * @package kalanis\kw_table\core\Table\Columns
 */
trait TWrappers
{
    /** @var array<string, string|array<string, string>> */
    protected $wrappers = [];

    /**
     * Add wrap tag
     * @param string $htmlTag
     * @param string|array<string, string> $attributes
     * @return $this
     */
    public function addWrapper(string $htmlTag, $attributes = '')
    {
        $this->wrappers[$htmlTag] = $attributes;
        return $this;
    }

    /**
     * Format data into tag with attributes
     * @param string $data
     * @return string
     */
    protected function formatData(string $data): string
    {
        foreach ($this->wrappers as $tag => $attribute) {
            if (is_array($attribute)) {
                $fill = '';
                foreach ($attribute as $k => $v) {
                    $fill .= sprintf('%s="%s"', $k, $v);
                }
            } else {
                $fill = $attribute;
            }
            $data = sprintf('<%s %s>%s</%s>', $tag, $fill, $data, $tag);
        }
        return $data;
    }
}
