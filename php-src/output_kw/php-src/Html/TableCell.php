<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableCell extends ATemplate
{
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-cell.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{CELL_CONTENT}');
        $this->addInput('{CELL_STYLE}');
    }

    public function setData(string $cellContent, string $cellStyle = ''): self
    {
        $this->updateItem('{CELL_CONTENT}', $cellContent);
        $this->updateItem('{CELL_STYLE}', $cellStyle);
        return $this;
    }
}
