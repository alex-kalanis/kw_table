<?php

namespace kalanis\kw_table\Table\Output;


use kalanis\kw_table\Table;


class HtmlDirectRender extends AOutput
{
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
