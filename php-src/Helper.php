<?php

namespace kalanis\kw_table;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\Sources;
use kalanis\kw_forms\Adapters;
use kalanis\kw_forms\Exceptions\FormsException;
use kalanis\kw_forms\Form;
use kalanis\kw_input\Interfaces as InputInterface;
use kalanis\kw_pager\BasicPager;
use kalanis\kw_paging\Positions;
use kalanis\kw_paging\Render;
use kalanis\kw_table\Connector\Form\KwForm;
use kalanis\kw_table\Connector\PageLink;
use kalanis\kw_table\Table\Output;
use kalanis\kw_table\Table\Sorter;


/**
 * Class Helper
 * @package kalanis\kw_table
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
     * @param InputInterface\IVariables $inputs
     * @param string $alias
     * @return $this
     * @throws FormsException
     */
    public function fillKwPage(InputInterface\IVariables $inputs, string $alias = 'filter'): self
    {
        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $form = new Form($alias);
        $this->table->addHeaderFilter(new KwForm($form));
        $form->setInputs($inputVariables, $inputFiles);

        // sorter links
        $sorter = new Sorter(new Handler(new Sources\Inputs($inputs)));
        $this->table->addSorter($sorter);

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\Inputs($inputs)), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\SimplifiedPager(new Positions($pager), $pageLink));

        return $this;
    }

    /**
     * @param InputInterface\IVariables $inputs
     * @param string $alias
     * @return $this
     * @throws FormsException
     */
    public function fillKwCli(InputInterface\IVariables $inputs, string $alias = 'filter'): self
    {
        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $form = new Form($alias);
        $this->table->addHeaderFilter(new KwForm($form));
        $form->setInputs($inputVariables, $inputFiles);

        // sorter links
        $sorter = new Sorter(new Handler(new Sources\Inputs($inputs)));
        $this->table->addSorter($sorter);

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\Inputs($inputs)), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\CliPager(new Positions($pager)));

        // output
        $this->table->setOutput(new Output\Cli($this->table));

        return $this;
    }

    /**
     * @param InputInterface\IVariables $inputs
     * @param string $alias
     * @return $this
     * @throws FormsException
     */
    public function fillKwJson(InputInterface\IVariables $inputs, string $alias = 'filter'): self
    {
        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $form = new Form($alias);
        $this->table->addHeaderFilter(new KwForm($form));
        $form->setInputs($inputVariables, $inputFiles);

        // sorter links
        $sorter = new Sorter(new Handler(new Sources\Inputs($inputs)));
        $this->table->addSorter($sorter);

        // pager
        $pager = new BasicPager();
        $pageLink = new PageLink(new Handler(new Sources\Inputs($inputs)), $pager);
        $pager->setActualPage($pageLink->getPageNumber());
        $this->table->addPager(new Render\CliPager(new Positions($pager)));

        // output
        $this->table->setOutput(new Output\Json($this->table));

        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
