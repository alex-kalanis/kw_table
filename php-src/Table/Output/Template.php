<?php

namespace kalanis\kw_table\Table\Output;


use kalanis\kw_table\Table;


/**
 * Class Template
 * @package kalanis\kw_table\Table\Output
 * Flush table into template
 */
class Template implements ITemplate
{
    protected $templatePath = null;

    public function __construct()
    {
        $this->templatePath = __DIR__ . '/../../../shared-templates/table.phtml';
    }

    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
    }

    public function render(Table $table): string
    {
        ob_start();
        include($this->templatePath);
        return ob_get_clean();
    }
}
