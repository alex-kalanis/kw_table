<?php

namespace kalanis\kw_table\core\Table;


use kalanis\kw_connect\core\AIterator;
use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Table\IRule;
use kalanis\kw_table\core\TableException;


/**
 * Class AStyle
 * @package kalanis\kw_table\core\Table
 * Columns styling
 */
abstract class AStyle extends AIterator
{
    /** @var array<int, Internal\Attributes> */
    protected $styles = [];
    /** @var string|int */
    protected $sourceName = '';
    /** @var array<string, array<Internal\Attributes>> */
    protected $attributes = [];

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
     * @param array<string|int, string|IRule> $arguments
     * @return mixed|null|void
     */
    public function __call($function, $arguments)
    {
        $this->attributes[$function][] = new Internal\Attributes(
            (isset($arguments[2]) && is_string($arguments[2]) ? $arguments[2] : ''),
            (isset($arguments[0]) && is_string($arguments[0]) ? $arguments[0] : ''),
            (isset($arguments[1]) && ($arguments[1] instanceof IRule) ? $arguments[1] : null)
        );
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
     * @param string|int $sourceName
     */
    public function style(string $style, ?IRule $condition, $sourceName = ''): void
    {
        $this->styles[] = new Internal\Attributes($sourceName, $style, $condition);
    }

    /**
     * Return attribute content by obtained conditions
     * @param IRow $source
     * @throws ConnectException
     * @throws TableException
     * @return string
     */
    public function getCellStyle(IRow $source): string
    {
        return $this->getAttributes($source) . $this->getStyleAttribute($source);
    }

    /**
     * Return all attributes to output
     * @param IRow $source
     * @throws ConnectException
     * @throws TableException
     * @return string
     */
    public function getAttributes(IRow $source): string
    {
        $return = [];
        foreach ($this->attributes as $key => $attr) {
            $attribute = [];
            foreach ($attr as $style) {
                /** @var Internal\Attributes $style */
                if (empty($style->getCondition()) || $this->isStyleApplied($source, $style)) {
                    $attribute[] = $this->getAttributeRealValue($source, $style->getProperty());
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
     * @throws ConnectException
     * @return string
     */
    protected function getAttributeRealValue(IRow $source, string $value): string
    {
        if (preg_match('/value\:(.*)/i', $value, $matches)) {
            return strval($this->getOverrideValue($source, $matches[1]));
        } else {
            return strval($value);
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
     * @throws ConnectException
     * @throws TableException
     * @return string
     */
    protected function getStyleAttribute(IRow $source)
    {
        $return = [];
        foreach ($this->styles as $style) {
            /** @var Internal\Attributes $style */
            if (empty($style->getCondition()) || $this->isStyleApplied($source, $style)) {
                $return[] = $style->getProperty();
            }
        }

        return (!empty($return)) ? ' style="' . implode('; ', $return) . '"' : '';
    }

    /**
     * Apply style?
     * @param IRow $source
     * @param Internal\Attributes $style
     * @throws ConnectException
     * @throws TableException
     * @return bool
     */
    protected function isStyleApplied(IRow $source, Internal\Attributes $style): bool
    {
        $property = (!empty($style->getColumnName())) ? $style->getColumnName() : $this->getSourceName();
        return (bool) $style->getCondition()->validate($this->getOverrideValue($source, $property));
    }

    /**
     * @return string|int
     */
    abstract public function getSourceName();

    /**
     * @param IRow $source
     * @param string|int $overrideProperty
     * @throws ConnectException
     * @return mixed
     */
    protected function getOverrideValue(IRow $source, $overrideProperty)
    {
        return $source->getValue($overrideProperty);
    }
}
