<?php

namespace kalanis\kw_table\core\Table;


use kalanis\kw_connect\core\AIterator;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Table\IRule;


/**
 * Class AStyle
 * @package kalanis\kw_table\core\Table
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

    protected function getIterableName(): string
    {
        return 'attributes';
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
     * Add colors from array -> color => conditions
     * @param IRule[] $data
     */
    public function colorizeArray(array $data): void
    {
        foreach ($data as $colour => $condition) {
            $this->colorize($colour, $condition);
        }
    }

    /**
     * When condition is met colour the cell
     * @param string $colour
     * @param IRule|null $condition
     */
    public function colorize(string $colour, ?IRule $condition): void
    {
        $this->style('background-color: ' . $colour, $condition);
    }

    /**
     * When condition value equals current value then add cell style
     * @param string $style
     * @param IRule|null $condition
     * @param string|null $sourceName
     */
    public function style(string $style, ?IRule $condition, ?string $sourceName = null): void
    {
        $this->styles[] = [
            static::KEY_STYLE => $style,
            static::KEY_CONDITION => $condition,
            static::KEY_OVERRIDE => $sourceName
        ];
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
                    $attribute[] = $this->getAttributeRealValue($source, $style[static::KEY_STYLE]);
                }
            }
            $return[] = $key . '="' . $this->joinAttributeParts($attribute) . '"';
        }

        return $this->joinAttributeParts($return);
    }

    /**
     * Returns attribute value with checking if we do not want any value from row
     * @param IRow $source
     * @param string $value
     * @return mixed
     */
    protected function getAttributeRealValue(IRow $source, string $value)
    {
        if (preg_match('/value\:(.*)/i', $value, $matches)) {
            return $this->getOverrideValue($source, $matches[1]);
        } else {
            return $value;
        }
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
     * Merge attribute Style - different for a bit different ordering
     * @param IRow $source
     * @return string
     */
    protected function getStyleAttribute(IRow $source)
    {
        $return = [];
        foreach ($this->styles as $style) {
            if (empty($style[static::KEY_CONDITION]) || $this->isStyleApplied($source, $style)) {
                $return[] = $style[static::KEY_STYLE];
            }
        }

        return (!empty($return)) ? ' style="' . implode('; ', $return) . '"' : '';
    }

    /**
     * Apply style?
     * @param IRow $source
     * @param array $style
     * @return bool
     */
    protected function isStyleApplied(IRow $source, array $style): bool
    {
        $property = (!empty($style[static::KEY_OVERRIDE])) ? $style[static::KEY_OVERRIDE] : $this->getSourceName();
        return (bool)$style[static::KEY_CONDITION]->validate($this->getOverrideValue($source, $property));
    }

    /**
     * @return string
     */
    abstract public function getSourceName(): string;

    protected function getOverrideValue(IRow $source, string $overrideProperty)
    {
        return $source->getValue($overrideProperty);
    }
}
