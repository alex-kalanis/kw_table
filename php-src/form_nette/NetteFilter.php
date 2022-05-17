<?php

namespace kalanis\kw_table\form_nette;


use kalanis\kw_table\core\Interfaces\Form\IField;
use kalanis\kw_table\core\Interfaces\Form\IFilterForm;
use kalanis\kw_table\core\TableException;
use Nette\Application\UI\Form as BaseForm;
use Nette\Bridges\FormsLatte\Runtime;
use Nette\Forms\Controls\BaseControl;


/**
 * Class NetteFilter
 * @package kalanis\kw_table\form_nette
 * Connect with Nette forms
 */
class NetteFilter implements IFilterForm
{
    /** @var BaseForm */
    protected $form;
    /** @var bool */
    protected $formProcess = null;
    /** @var string[]|int[] */
    protected $formData = [];

    public function __construct(BaseForm $form)
    {
        $this->form = $form;
    }

    public function addField(IField $field): void
    {
        if (!$field instanceof Fields\AField) {
            throw new TableException('Not an instance of \kalanis\kw_table\form_nette\Fields\AField.');
        }

        $field->prepareAlias();
        $field->setForm($this->form);
        $field->add();
    }

    public function setValue(string $alias, $value): void
    {
        $this->form[$this->prepareAlias($alias)] = $value;
    }

    public function getValues(): array
    {
        $this->process();
        return $this->formData;
    }

    public function getValue(string $alias)
    {
        if ($this->process()) {
            return $this->formData[$this->prepareAlias($alias)];
        }

        return null;
    }

    public function getFormName(): string
    {
        return $this->form->getName();
    }

    public function renderStart(): string
    {
        return Runtime::renderFormBegin($this->form, []);
    }

    public function renderEnd(): string
    {
        return Runtime::renderFormEnd($this->form);
    }

    public function renderField(string $alias): string
    {
        return $this->form[$this->prepareAlias($alias)]->getControl()->render();
    }

    protected function process(): bool
    {
        if (isset($this->formProcess)) {
            return $this->formProcess;
        }
        $formData = [];
        foreach ($this->form->getControls() AS $controls) {
            /** @var BaseControl $controls */
            $name = $controls->getName();
            $value = null;

            if (isset($_GET[$name])) {
                $value = $_GET[$name];
                $controls->setValue($value);
            }
            $formData[$name] = $controls->getValue();
        }
        $this->formProcess = true;
        $this->formData = $formData;
        return $this->formProcess;
    }

    /**
     * Nette form disallow '.' in name, so we change it to '_'
     * @param string $alias
     * @return string
     */
    public function prepareAlias($alias)
    {
        return str_replace('.', '_', $alias);
    }
}
