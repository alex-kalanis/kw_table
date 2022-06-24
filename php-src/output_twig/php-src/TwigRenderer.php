<?php

namespace kalanis\kw_table\output_twig;


use kalanis\kw_table\core\Table;
use Twig\Environment;
use Twig\Loader\ArrayLoader;


/**
 * Class TwigRenderer
 * @package kalanis\kw_table\output_direct
 * Direct renderer into Twig template (Symfony)
 */
class TwigRenderer extends Table\AOutput
{
    /** @var string */
    protected $templatePath = '';
    /** @var ArrayLoader */
    protected $loader = null;
    /** @var Environment */
    protected $twig = null;

    public function __construct(Table $table)
    {
        parent::__construct($table);
        $this->templatePath = __DIR__ . '/../shared-templates/table.html.twig';
        $this->loader = new ArrayLoader();
        $this->twig = new Environment($this->loader);
    }

    public function render(): string
    {
        $source = strval(@file_get_contents($this->templatePath));
        $this->loader->setTemplate('kw_table', $source);
        return $this->twig->render('kw_table', ['table' => $this->table]);
    }
}
