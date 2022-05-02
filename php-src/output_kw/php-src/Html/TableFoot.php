<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableFoot extends ATemplate
{
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-foot.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{FOOT_CONTENT}');
    }

    public function setData(string $footContent): self
    {
        $this->updateItem('{FOOT_CONTENT}', $footContent);
        return $this;
    }
}
