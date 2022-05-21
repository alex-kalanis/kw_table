<?php

namespace kalanis\kw_table\nette;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render;
use kalanis\kw_table\core\Connector\PageLink;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Order;
use kalanis\kw_table\form_nette\NetteFilter;
use kalanis\kw_table\output_latte\LatteRenderer;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;


/**
 * Class Helper
 * @package kalanis\kw_table\nette
 * Helper with table initialization
 */
class Helper
{
    /** @var Table */
    protected $table = null;

    public function __construct()
    {
        $this->table = new Table();
    }

    /**
     * @param IContainer $container
     * @param string $alias
     * @return $this
     */
    public function fillNetteTable(IContainer $container, string $alias = 'filter'): self
    {
        // filter form
        $form = new Form($container, $alias);
        $this->table->addHeaderFilter(new NetteFilter($form));

        // order links
        $this->table->addOrder(new Order(new Handler(new Sources\ServerRequest())));

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\ServerRequest()), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\SimplifiedPager(new Positions($pager), $pageLink));

        // output
        $this->table->setOutput(new LatteRenderer($this->table));

        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
