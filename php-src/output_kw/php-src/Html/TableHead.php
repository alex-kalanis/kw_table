<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableHead extends ATemplate
{
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-head.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{HEAD_CONTENT}');
    }

    public function setData(string $headContent): self
    {
        $this->updateItem('{HEAD_CONTENT}', $headContent);
        return $this;
    }
}
