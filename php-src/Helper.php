<?php

namespace kalanis\kw_table;


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
use kalanis\kw_table\UrlHandler;


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
     * @param InputInterface\IInputs $inputs
     * @param string $alias
     * @return $this
     * @throws FormsException
     */
    public function fillKwPage(InputInterface\IInputs $inputs, string $alias = 'filter'): self
    {
        $url = new UrlHandler\UrlHandler($inputs);

        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputVariables->loadEntries(InputInterface\IEntry::SOURCE_GET);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $inputFiles->loadEntries(InputInterface\IEntry::SOURCE_GET);
        $form = new Form($alias);
        $form->setInputs($inputVariables, $inputFiles);
        $this->table->addHeaderFilter(new KwForm($form));

        // sorter links
        $sorter = new Sorter(new UrlHandler\UrlVariable($url));
        $this->table->addSorter($sorter);

        // pager
        $pager = new BasicPager();
        $this->table->addPager(new Render\SimplifiedPager(new Positions($pager), new PageLink(new UrlHandler\UrlVariable($url), $pager)));

        return $this;
    }

    /**
     * @param InputInterface\IInputs $inputs
     * @param string $alias
     * @return $this
     * @throws FormsException
     */
    public function fillKwCli(InputInterface\IInputs $inputs, string $alias = 'filter'): self
    {
        $url = new UrlHandler\UrlHandler($inputs);

        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputVariables->loadEntries(InputInterface\IEntry::SOURCE_GET);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $inputFiles->loadEntries(InputInterface\IEntry::SOURCE_GET);
        $form = new Form($alias);
        $form->setInputs($inputVariables, $inputFiles);
        $this->table->addHeaderFilter(new KwForm($form));

        // sorter links
        $sorter = new Sorter(new UrlHandler\UrlVariable($url));
        $this->table->addSorter($sorter);

        // pager
        $pager = new BasicPager();
        $this->table->addPager(new Render\CliPager(new Positions($pager)));

        // output
        $this->table->setOutput(new Output\Cli($this->table));

        return $this;
    }

    /**
     * @param InputInterface\IInputs $inputs
     * @param string $alias
     * @return $this
     * @throws FormsException
     */
    public function fillKwJson(InputInterface\IInputs $inputs, string $alias = 'filter'): self
    {
        $url = new UrlHandler\UrlHandler($inputs);

        // filter form
        $inputVariables = new Adapters\InputVarsAdapter($inputs);
        $inputVariables->loadEntries(InputInterface\IEntry::SOURCE_GET);
        $inputFiles = new Adapters\InputFilesAdapter($inputs);
        $inputFiles->loadEntries(InputInterface\IEntry::SOURCE_GET);
        $form = new Form($alias);
        $form->setInputs($inputVariables, $inputFiles);
        $this->table->addHeaderFilter(new KwForm($form));

        // sorter links
        $sorter = new Sorter(new UrlHandler\UrlVariable($url));
        $this->table->addSorter($sorter);

        // pager
        $pager = new BasicPager();
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
