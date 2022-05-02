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
use kalanis\kw_table\core\Table\Sorter;
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
        $this->table->addHeaderFilter(new KwFilter($form));
        $form->setInputs($inputVariables, $inputFiles);

        // sorter links
        $sorter = new Sorter(new Handler(new Sources\Inputs($inputs)));
        $this->table->addSorter($sorter);

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
        $this->table->addHeaderFilter(new KwFilter($form));
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
        $this->table->setOutput(new CliRenderer($this->table));

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
        $this->table->addHeaderFilter(new KwFilter($form));
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
        $this->table->setOutput(new JsonRenderer($this->table));

        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
