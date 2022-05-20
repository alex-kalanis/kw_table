<?php

namespace kalanis\kw_table\output_latte;


use kalanis\kw_table\core\Table;
use Latte\Engine;


/**
 * Class LatteRenderer
 * @package kalanis\kw_table\output_latte
 * Render output in html templates from Nette\Latte
 * @link https://latte.nette.org/en/guide
 */
class LatteRenderer extends Table\AOutput
{
    /** @var Engine */
    protected $engine = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->engine = new Engine();
    }

    public function render(): string
    {
        return $this->engine->renderToString(
            realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'shared-templates' . DIRECTORY_SEPARATOR . 'table.latte'),
            [
                'table' => $this->table,
            ]
        );
    }
}
