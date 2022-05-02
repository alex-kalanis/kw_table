<?php

namespace kalanis\kw_table\output_kw\Html;


use kalanis\kw_templates\ATemplate;
use kalanis\kw_templates\Template\TFile;


class TableBase extends ATemplate
{
    use TFile;

    protected function templatePath(): string
    {
        return __DIR__ . '/../../shared-templates/table-base.html';
    }

    protected function fillInputs(): void
    {
        $this->addInput('{TABLE_PAGER_HEAD}');
        $this->addInput('{TABLE_PAGER_FOOT}');
        $this->addInput('{TABLE_SORTER_FILTER}');
        $this->addInput('{TABLE_HEAD_FILTER}');
        $this->addInput('{TABLE_CELLS}');
        $this->addInput('{TABLE_FOOTER_FILTER}');
        $this->addInput('{TABLE_SCRIPT}');
        $this->addInput('{FILTER_START}');
        $this->addInput('{FILTER_END}');
        $this->addInput('{CLASSES_IN_STRING}');
    }

    public function addPagerHead(string $pager): self
    {
        $this->updateItem('{TABLE_PAGER_HEAD}', $pager);
        return $this;
    }

    public function addPagerFoot(string $pager): self
    {
        $this->updateItem('{TABLE_PAGER_FOOT}', $pager);
        return $this;
    }

    public function addFilter(string $filterStart, string $filterEnd): self
    {
        $this->updateItem('{FILTER_START}', $filterStart);
        $this->updateItem('{FILTER_END}', $filterEnd);
        return $this;
    }

    public function addScript(string $script): self
    {
        $this->updateItem('{TABLE_SCRIPT}', $script);
        return $this;
    }

    public function setData(string $cells, string $header = '', string $headFilter = '', string $footerFilter = '', string $classes = ''): self
    {
        $this->updateItem('{TABLE_CELLS}', $cells);
        $this->updateItem('{TABLE_SORTER_FILTER}', $header);
        $this->updateItem('{TABLE_HEAD_FILTER}', $headFilter);
        $this->updateItem('{TABLE_FOOTER_FILTER}', $footerFilter);
        $this->updateItem('{CLASSES_IN_STRING}', $classes);
        return $this;
    }
}
