<?php

namespace kalanis\kw_table\kw;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources;
use kalanis\kw_forms\Adapters;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_forms\Form;
use kalanis\kw_input\Interfaces as InputInterface;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render;
use kalanis\kw_table\core\Connector\PageLink;
use kalanis\kw_table\core\Table;
use kalanis\kw_table\core\Table\Order;
use kalanis\kw_table\form_kw\KwFilter;
use kalanis\kw_table\output_cli\CliRenderer;
use kalanis\kw_table\output_json\JsonRenderer;
use kalanis\kw_table\output_kw\KwRenderer;


/**
 * Class Helper
 * @package kalanis\kw_table\kw
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
     * @param InputInterface\IFiltered $inputs
     * @param string $alias
     * @throws FormsException
     * @return $this
     */
    public function fillKwPage(InputInterface\IFiltered $inputs, string $alias = 'filter'): self
    {
        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $form = new Form($alias);
        $this->table->addHeaderFilter(new KwFilter($form));
        $form->setInputs($inputVariables, $inputFiles);

        // order links
        $this->table->addOrder(new Order(new Handler(new Sources\Inputs($inputs))));

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\Inputs($inputs)), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\SimplifiedPager(new Positions($pager), $pageLink));

        // output
        $this->table->setOutput(new KwRenderer($this->table));

        return $this;
    }

    /**
     * @param InputInterface\IFiltered $inputs
     * @param int $currentPage
     * @param string $alias
     * @throws FormsException
     * @return $this
     */
    public function fillKwCli(InputInterface\IFiltered $inputs, ?int $currentPage = null, string $alias = 'filter'): self
    {
        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $form = new Form($alias);
        $form->setMethod(InputInterface\IEntry::SOURCE_CLI);
        $this->table->addHeaderFilter(new KwFilter($form, false));
        $form->setInputs($inputVariables, $inputFiles);

        // order links
        $this->table->addOrder(new Order(new Handler(new Sources\Inputs($inputs))));

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\Inputs($inputs)), $pager);
        if (!is_null($currentPage)) {
            $pageLink->setPageNumber($currentPage);
        }
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\CliPager(new Positions($pager)));

        // output
        $this->table->setOutput(new CliRenderer($this->table));

        return $this;
    }

    /**
     * @param InputInterface\IFiltered $inputs
     * @param string $alias
     * @throws FormsException
     * @return $this
     */
    public function fillKwJson(InputInterface\IFiltered $inputs, string $alias = 'filter'): self
    {
        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $form = new Form($alias);
        $this->table->addHeaderFilter(new KwFilter($form));
        $form->setInputs($inputVariables, $inputFiles);

        // order links
        $this->table->addOrder(new Order(new Handler(new Sources\Inputs($inputs))));

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\Inputs($inputs)), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\CliPager(new Positions($pager)));

        // output
        $this->table->setOutput(new JsonRenderer($this->table));

        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
