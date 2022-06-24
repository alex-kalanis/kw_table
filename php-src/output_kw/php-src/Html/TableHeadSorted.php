<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableHeadSorted extends ATemplate
{
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-head-sorted.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{HEAD_CONTENT}');
        $this->addInput('{SORTER_LINK}');
    }

    public function setData(string $headContent, ?string $sorterLink): self
    {
        $this->updateItem('{HEAD_CONTENT}', $headContent);
        $this->updateItem('{SORTER_LINK}', is_null($sorterLink) ? '#' : $sorterLink);
        return $this;
    }
}
