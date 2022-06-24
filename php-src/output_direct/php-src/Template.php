<?php

namespace kalanis\kw_table\output_direct;


use kalanis\kw_table\core\Table;


/**
 * Class Template
 * @package kalanis\kw_table\output_direct
 * Flush table into template
 */
class Template implements ITemplate
{
    /** @var string */
    protected $templatePath = null;

    public function __construct()
    {
        $this->templatePath = __DIR__ . '/../shared-templates/table.phtml';
    }

    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
    }

    public function render(Table $table): string
    {
        ob_start();
        include($this->templatePath);
        return strval(ob_get_clean());
    }
}
