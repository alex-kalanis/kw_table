<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableRow extends ATemplate
{
    use TAppend;
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-row.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{ROW_STYLE}');
        $this->addInput('{ROW_CONTENT}');
    }

    public function addCell(string $cellContent): self
    {
        $this->appendContent('{ROW_CONTENT}', $cellContent);
        return $this;
    }

    public function setData(string $rowStyle = ''): self
    {
        $this->updateItem('{ROW_STYLE}', $rowStyle);
        $this->updateItem('{ROW_CONTENT}', '');
        return $this;
    }
}
