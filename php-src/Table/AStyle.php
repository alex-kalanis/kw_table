<?php

namespace kalanis\kw_table\Table;


use kalanis\kw_table\AIterator;
use kalanis\kw_table\Interfaces\Table\IRow;
use kalanis\kw_table\Interfaces\Table\IRule;


/**
 * Class AStyle
 * @package kalanis\kw_table\Table
 * Columns styling
 */
abstract class AStyle extends AIterator
{
    const KEY_STYLE = 'style';
    const KEY_CONDITION = 'condition';
    const KEY_OVERRIDE = 'override';

    protected $styles = [];
    protected $sourceName = '';

    protected $attributes = [];

    /** @var string[] */
    protected $wrappers = [];

    abstract protected function getOverrideValue(IRow $source, $override);

    protected function getIterableName(): string
    {
        return 'attributes';
    }

    /**
     * Add attribute
     * @param string $function
     * @param string[] $arguments
     */
    public function __call($function, $arguments)
    {
        $this->attributes[$function][] = [
            static::KEY_STYLE => $arguments[0],
            static::KEY_CONDITION => (isset($arguments[1]) ? $arguments[1] : null),
            static::KEY_OVERRIDE => (isset($arguments[2]) ? $arguments[2] : null)
        ];
    }

    /**
     * When condition value equals current value then add cell style
     * @param string $style
     * @param IRule $condition
     * @param string|null $sourceName
     */
    public function style(string $style, IRule $condition, ?string $sourceName = null): void
    {
        $this->styles[] = [static::KEY_CONDITION => $condition, static::KEY_STYLE => $style, static::KEY_OVERRIDE => $sourceName];
    }

    /**
     * Apply style?
     * @param IRow $source
     * @param array $style
     * @return bool
     */
    protected function isStyleApplied(IRow $source, array $style): bool
    {
        $property = ((isset($style[static::KEY_OVERRIDE]) && !empty($style[static::KEY_OVERRIDE])) ? $style[static::KEY_OVERRIDE] : $this->sourceName);

        if ($style[static::KEY_CONDITION]->validate($this->getOverrideValue($source, $property))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return all attributes to output
     * @param IRow $source
     * @return string
     */
    public function getAttributes(IRow $source): string
    {
        $return = [];
        foreach ($this->attributes as $key => $attr) {
            $attribute = [];
            foreach ($attr as $style) {
                if (empty($style[static::KEY_CONDITION]) || $this->isStyleApplied($source, $style)) {
                    $attribute[] = $this->getAttributeRealValue($source, $style['style']);
                }
            }
            $return[] = $key . '="' . $this->joinAttributeParts($attribute) . '"';
        }

        return $this->joinAttributeParts($return);
    }

    /**
     * Merge attributes in array
     * @param string[] $values
     * @param string   $glue
     * @return string
     */
    protected function joinAttributeParts(array $values, string $glue = ' '): string
    {
        return implode($glue, $values);
    }

    /**
     * Returns attribute value with checking if we do not want any value from row
     * @param IRow $source
     * @param      $value
     * @return mixed
     */
    protected function getAttributeRealValue(IRow $source, $value)
    {
        if (preg_match('/value\:(.*)/i', $value, $matches)) {
            return $this->getOverrideValue($source, $matches[1]);
        } else {
            return $value;
        }
    }

    /**
     * Merge attribute Style - different for a bit different ordering
     * @param IRow $source
     * @return string
     */
    protected function getStyleAttribute(IRow $source)
    {
        $return = [];
        foreach ($this->styles as $style) {
            if ($this->isStyleApplied($source, $style)) {
                $return[] = $style[static::KEY_STYLE];
            }
        }

        return (!empty($return)) ? ' style="' . implode('; ', $return) . '"' : '';
    }

    /**
     * Return attribute content by obtained conditions
     * @param IRow $source
     * @return string
     */
    public function getCellStyle(IRow $source): string
    {
        return $this->getAttributes($source) . $this->getStyleAttribute($source);
    }

    /**
     * When condition is met colour the cell
     * @param string $colour
     * @param IRule $condition
     */
    public function colorize(string $colour, IRule $condition): void
    {
        $this->style('background-color: ' . $colour, $condition);
    }

    /**
     * Add colors from array -> color => conditions
     * @param IRule[] $data
     */
    public function colorizeArray(array $data): void
    {
        foreach ($data as $colour => $condition) {
            $this->style('background-color: ' . $colour, $condition);
        }
    }

    /**
     * Add CSS classes: class => condition
     * @param IRule[] $classes
     */
    public function classArray(array $classes): void
    {
        foreach ($classes as $class => $condition) {
            $this->__call('class', [$class, $condition]);
        }
    }
}
