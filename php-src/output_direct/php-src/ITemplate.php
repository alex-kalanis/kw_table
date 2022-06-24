<?php

namespace kalanis\kw_table\output_direct;


use kalanis\kw_table\core\Table;


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
    public function setTemplatePath(string $templatePath): void;
}
