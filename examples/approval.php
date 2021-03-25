<?php

use kalanis\kw_mapper\Storage;
use kalanis\kw_table\Connector\Form;
use kalanis\kw_table\Table\Columns;
use kalanis\kw_table\Table\Rules;


/**
 * Class FileApproval
 * Example of table with footer filters - that filters which has been made for processing data in extra step
 */
class FileApproval
{
    protected $table = null;

    public function __construct(\kalanis\kw_input\Interfaces\IInputs $inputs)
    {
        $helper = new \kalanis\kw_table\Helper();
        $helper->fillKwPage($inputs, 'approvalForm');
        $this->table = $helper->getTable();
    }

    /**
     * @param \kalanis\kw_mapper\Search\Search $search
     * @throws \kalanis\kw_mapper\MapperException
     */
    public function composeTable($search)
    {
        $this->table->addFooterFilter($this->table->getHeaderFilter()->getConnector()); // use that form in header which won't be used here
        $this->table->setDefaultFooterFilterFieldAttributes(['style' => 'width:100%']);

        $columnUserId = new Columns\Func('id', [$this, 'idLink']);
        $columnUserId->style('width:40px', new Rules\Always());
        $this->table->addSortedColumn('ID', $columnUserId, null, new Form\KwField\InputCallback([$this, 'footerLink']) );

        $this->table->addSortedColumn('Title', new Columns\RowData(['name','admins.adminId'], [$this, 'titleCallback']));
        $this->table->addSortedColumn('Size', new Columns\MultiColumnLink('fileSize', [new Columns\Basic('id')], [$this, 'fileSize']));

        $columnAdded = new Columns\Date('added', 'Y-m-d H:i:s');
        $columnAdded->style('width:150px', new Rules\Always());
        $this->table->addSortedColumn('Added', $columnAdded);

        $columnActions = new Columns\Multi('&nbsp;&nbsp;');
        $columnActions->addColumn(new Columns\Func('id', array($this, 'viewLink')));
        $columnActions->style('width:100px', new Rules\Always());

        $this->table->addColumn('Actions', $columnActions, null, new Form\KwField\Options(static::getStatuses(), [
            'id' => 'multiselectChange',
            'data-toggle' => 'modal-ajax-wide-table',
        ]));
        $columnCheckbox = new Columns\Multi('&nbsp;&nbsp;', 'checkboxes');
        $columnCheckbox->addColumn(new Columns\MultiSelectCheckbox('id'));
        $this->table->addColumn('', $columnCheckbox, null, new Form\KwField\MultiSelect( '0', ['id' => 'multiselectAll']) );

        $this->table->setDefaultSorting('id', \kalanis\kw_mapper\Interfaces\IQueryBuilder::ORDER_DESC);
        $this->table->addDataSource(new \kalanis\kw_table\Connector\Sources\Search($search));
    }

    public function titleCallback($params)
    {
        list($title, $adminId) = $params; // because example of passing multiple values
        return $title;
    }

    public function idLink($id)
    {
        return '<a href="/display/' . $id . '/">' . $id . '</a>';
    }

    public function footerLink($args)
    {
        return 'Set to:';
    }

    public function fileSize($data)
    {
        // example of another way to get data through
        $ormVideo = $this->table->getDataSource()->getByKey($data[1]);
        $filesizeMB = round(($data[0] / 10), 2);
        return $filesizeMB . ' kB';
    }

    public function getTable(): \kalanis\kw_table\Table
    {
        return $this->table;
    }
}
