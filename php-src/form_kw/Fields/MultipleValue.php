<?php

namespace kalanis\kw_table\form_kw\Fields;


use kalanis\kw_connect\core\Interfaces\IIterableConnector;
use kalanis\kw_forms\Form;
use kalanis\kw_table\core\Connector\AMultipleValue;


/**
 * Class MultipleValue
 * @package kalanis\kw_table\form_kw\Fields
 */
class MultipleValue extends AMultipleValue
{
    /** @var AField */
    protected $field = null;

    public function __construct(AField $field, ?string $label = null, string $alias = '')
    {
        $this->field = $field;
        $this->alias = $alias;
        $this->label = $label;
    }

    public function getAlias(): string
    {
        return empty($this->alias)
            ? (empty($this->columnName)
                ? $this->field->getAlias() : $this->columnName)
            : $this->alias
        ;
    }

    public function getField(): AField
    {
        return $this->field;
    }

    public function setDataSourceConnector(IIterableConnector $dataSource): void
    {
        $this->field->setDataSourceConnector($dataSource);
    }

    public function setForm(Form $form): void
    {
        $this->field->setForm($form);
    }

    public function add(): void
    {
        $this->field->setAlias($this->getAlias());
        $this->field->add();
    }

    public function renderContent(): string
    {
        $control = $this->field->getForm()->/** @scrutinizer ignore-call */getControl($this->getAlias());
        $control->setLabel($this->getLabel());
        return $control->render();
    }
}
