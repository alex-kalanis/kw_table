<?php

namespace kalanis\kw_table\Connector\Form;


use kalanis\kw_forms\Form;
use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Interfaces\Connector;


/**
 * Class KwForm
 * @package Admin\Listing\Connector
 */
class KwForm implements Connector\IForm
{
    /** @var Form */
    protected $form;
    /** @var bool */
    protected $formProcess = false;
    /** @var string[] */
    protected $formData = [];

    public function __construct(Form $form)
    {
        $form->setMethod('get');
        $form->addHidden('apply' . ucfirst($form->getAlias()), 'apply');
        $this->form = $form;
    }

    public function addField(Connector\IField $field): void
    {
        if (!$field instanceof KwField\AField) {
            throw new MapperException('Not an instance of \kalanis\kw_table\Connector\Form\KwField\AField.');
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
        return $this->form->getAttribute('name');
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
        return $this->form->getControl($alias)->renderInput();
    }

    protected function process(): bool
    {
        if ($this->formProcess) {
            return $this->formProcess;
        }

        $this->formProcess = $this->form->process('apply' . ucfirst($this->form->getAlias()));
        $this->formData = $this->form->getValues();
        return $this->formProcess;
    }
}
