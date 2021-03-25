<?php

namespace kalanis\kw_table\Connector\Form;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Interfaces\Connector;
use Nette\Application\UI\Form;


/**
 * Class NetteForm
 * @package Admin\Listing\Connector
 * @codeCoverageIgnore Contains external framework
 * Connect with Nette forms
 */
class NetteForm implements Connector\IForm
{
    /** @var Form */
    protected $form;
    /** @var bool */
    protected $formProcess = null;
    /** @var string[]|int[] */
    protected $formData = [];

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function addField(Connector\IField $field): void
    {
        if (!$field instanceof NetteField\AField) {
            throw new MapperException('Not an instance of \kalanis\kw_table\Connector\Form\NetteField\AField.');
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
        return \Nette\Bridges\FormsLatte\Runtime::renderFormBegin($this->form, []);
    }

    public function renderEnd(): string
    {
        return \Nette\Bridges\FormsLatte\Runtime::renderFormEnd($this->form);
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
        /** @var \Nette\Forms\Controls\BaseControl $controls */
        foreach ($this->form->getControls() AS $controls) {
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
