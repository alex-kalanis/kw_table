<?php

namespace kalanis\kw_table\form_kw;


use kalanis\kw_forms\Form as BaseForm;
use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\Interfaces\Form\IFilterForm;
use kalanis\kw_table\core\TableException;


/**
 * Class KwFilter
 * @package kalanis\kw_table\form_kw
 * Use KwForm as source of params
 */
class KwFilter implements IFilterForm
{
    /** @var BaseForm */
    protected $form;
    /** @var bool */
    protected $formProcess = false;
    /** @var array<string, string|int|float|bool|null> */
    protected $formData = [];

    public function __construct(BaseForm $form, bool $useApplyCheck = true)
    {
        $form->setMethod('get');
        if ($useApplyCheck) {
            $form->addHidden('apply' . ucfirst(strval($form->getAlias())), 'apply');
        }
        $this->form = $form;
    }

    public function addField(IField $field): void
    {
        if (!$field instanceof Fields\AField) {
            throw new TableException('Not an instance of \kalanis\kw_table\form_kw\Fields\AField.');
        }

        $field->setForm($this->form);
        $field->add();
    }

    public function setValue(string $alias, $value): void
    {
        $this->form->setValue($alias, $value);
    }

    public function getValues(): array
    {
        $this->process();
        return $this->formData;
    }

    public function getValue(string $alias)
    {
        if ($this->process()) {
            return $this->formData[$alias];
        }

        return null;
    }

    public function getFormName(): string
    {
        return strval($this->form->getAttribute('name'));
    }

    public function renderStart(): string
    {
        return $this->form->renderStart();
    }

    public function renderEnd(): string
    {
        return $this->form->renderEnd();
    }

    public function renderField(string $alias): string
    {
        $control = $this->form->getControl($alias);
        return $control ? $control->renderInput() : '';
    }

    protected function process(): bool
    {
        if ($this->formProcess) {
            return $this->formProcess;
        }

        $this->formProcess = $this->form->process('apply' . ucfirst(strval($this->form->getAlias())));
        $this->formData = $this->form->getValues();
        return $this->formProcess;
    }
}
