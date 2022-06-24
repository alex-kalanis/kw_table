<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IFilterFactory;
use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_forms\Exceptions\RenderException;
use kalanis\kw_forms\Form;
use kalanis\kw_table\core\Interfaces\Table\IFilterMulti;
use kalanis\kw_table\core\Interfaces\Table\IFilterRender;
use kalanis\kw_table\core\TableException;


/**
 * Class Multiple
 * @package kalanis\kw_table\form_kw\Fields
 * Simulates work with the form as it contains a multiple fields over column to process them as one
 * It behaves like "AND" over fields - entry must pass through every field
 * It is not probably possible to make "OR" variant due behavior underneath on storage connection (database, files) level
 * It's usable with limits GT, GTE, LT, LTE and string CONTAINS
 *
 * Example:
    $table->addSortedColumn('images.size', new Columns\Basic('size'), new Fields\Multiple([
        new Fields\MultipleValue(new Fields\NumFrom(), 'From'),
        new Fields\MultipleValue(new Fields\NumToWith(), 'To')
    ]));
 */
class Multiple extends AField implements IFilterRender, IFilterMulti
{
    /** @var MultipleValue[] */
    protected $fields = [];
    /** @var string */
    protected $separator = '<br />';

    /**
     * @param MultipleValue[] $fields
     * @param array<string, string> $attributes
     * @throws TableException
     */
    public function __construct(array $fields = [], array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setFields($fields);
    }

    public function setForm(Form $form): void
    {
        parent::setForm($form);
        foreach ($this->fields as &$field) {
            $field->setForm($form);
        }
    }

    public function setAlias(string $alias): void
    {
        parent::setAlias($alias);
        foreach ($this->fields as $i => &$field) {
            $field->setColumn($alias);
            $field->setAlias(sprintf('%s_%d', $alias, $i));
        }
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
        parent::setDataSourceConnector($dataSource);
        foreach ($this->fields as &$field) {
            $field->setDataSourceConnector($dataSource);
        }
    }

    /**
     * @param MultipleValue[] $fields
     * @throws TableException
     */
    public function setFields(array $fields): void
    {
        foreach ($fields as $i => $field) {
            if (!$field instanceof MultipleValue) {
                throw new TableException(sprintf('Field at position *%s* is type of *%s*, not instance of MultipleValue', $i, gettype($field)));
            }
        }
        $this->fields = $fields;
    }

    public function getFilterAction(): string
    {
        return IFilterFactory::ACTION_MULTIPLE;
    }

    public function add(): void
    {
        foreach ($this->fields as &$field) {
            $field->add();
        }
    }

    public function renderContent(): string
    {
        return implode($this->separator, array_map([$this, 'renderField'], $this->fields));
    }

    /**
     * @param MultipleValue $field
     * @throws RenderException
     * @return string
     */
    public function renderField(MultipleValue $field): string
    {
        return $field->renderContent();
    }

    /**
     * @return array<int, array<int, float|int|string|true>>
     */
    public function getPairs(): array
    {
        $values = [];
        foreach ($this->fields as $field) {
            $control = $this->form->/** @scrutinizer ignore-call */getControl($field->getAlias());
            if (!empty($control->getValue())) {
                $values[] = [$field->getField()->getFilterAction(), $control->getValue()];
            }
        }
        return $values;
    }
}
