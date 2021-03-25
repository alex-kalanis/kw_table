<?php

namespace kalanis\kw_table\Table\Output;


use kalanis\kw_table\Table;


interface ITemplate
{
    /**
     * @param Table $table
     * @return string
     */
    public function render(Table $table): string;

    /**
     * @param string $templatePath
     */
    public function setTemplatePath(string $templatePath);
}
