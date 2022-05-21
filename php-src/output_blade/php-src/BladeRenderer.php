<?php

namespace kalanis\kw_table\output_blade;


use kalanis\kw_table\core\Table;


/**
 * Class BladeRenderer
 * @package kalanis\kw_table\output_blade
 * Direct renderer into template engine Blade (Laravel)
 */
class BladeRenderer extends Table\AOutput
{
    protected $templatePath = '';

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->templatePath = __DIR__ . '/../shared-templates/table.blade.php';
    }

    public function render(): string
    {
        $source = @file_get_contents($this->templatePath);
        return \Illuminate\View\Compilers\BladeCompiler::render($source, ['table' => $this->table], true);
    }
}
