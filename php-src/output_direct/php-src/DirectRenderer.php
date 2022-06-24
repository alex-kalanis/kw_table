<?php

namespace kalanis\kw_table\output_direct;


use kalanis\kw_table\core\Table;


/**
 * Class DirectRenderer
 * @package kalanis\kw_table\output_direct
 * Direct renderer into PHP template
 */
class DirectRenderer extends Table\AOutput
{
    /** @var Template */
    protected $template = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->template = new Template();
    }

    public function getTemplate(): Template
    {
        return $this->template;
    }

    public function render(): string
    {
        return $this->template->render($this->table);
    }
}
