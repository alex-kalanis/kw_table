<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableScript extends ATemplate
{
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-script.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{FORM_NAME}');
    }

    public function setData(string $formName): self
    {
        $this->updateItem('{FORM_NAME}', $formName);
        return $this;
    }
}
