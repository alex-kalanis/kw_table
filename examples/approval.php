<?php

use kalanis\kw_mapper\Storage;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\form_kw\Fields as KwField;


/**
 * Class FileApproval
 * Example of table with footer filters - that filters which has been made for processing data in extra step
 */
class FileApproval
{
    protected $table = null;

    public function __construct(\kalanis\kw_input\Interfaces\IFiltered $inputs)
    {
        $helper = new \kalanis\kw_table\kw\Helper();
        $helper->fillKwPage($inputs, 'approvalForm');
        $this->table = $helper->getTable();
    }

    /**
     * @param \kalanis\kw_mapper\Search\Search $search
     * @throws \kalanis\kw_mapper\MapperException
     * @throws \kalanis\kw_connect\core\ConnectException
     * @throws \kalanis\kw_table\core\TableException
     */
    public function composeTable($search)
    {
        $this->table->addFooterFilter($this->table->getHeaderFilter()->getConnector()); // use that form in header which won't be used here
        $this->table->setDefaultFooterFilterFieldAttributes(['style' => 'width:100%']);

        $columnUserId = new Columns\Func('id', [$this, 'idLink']);
        $columnUserId->style('width:40px', new Rules\Always());
        $this->table->addOrderedColumn('ID', $columnUserId, null, new KwField\InputCallback([$this, 'footerLink']) );

        $this->table->addOrderedColumn('Title', new Columns\RowData(['name','admins.adminId'], [$this, 'titleCallback']));
        $this->table->addOrderedColumn('Size', new Columns\MultiColumnLink('fileSize', [new Columns\Basic('id')], [$this, 'fileSize']));

        $columnAdded = new Columns\Date('added', 'Y-m-d H:i:s');
        $columnAdded->style('width:150px', new Rules\Always());
        $this->table->addOrderedColumn('Added', $columnAdded);

        $columnActions = new Columns\Multi('&nbsp;&nbsp;');
        $columnActions->addColumn(new Columns\Func('id', [$this, 'viewLink']));
        $columnActions->style('width:100px', new Rules\Always());

        $this->table->addColumn('Actions', $columnActions, null, new KwField\Options(static::getStatuses(), [
            'id' => 'multiselectChange',
            'data-toggle' => 'modal-ajax-wide-table',
        ]));
        $columnCheckbox = new Columns\Multi('&nbsp;&nbsp;', 'checkboxes');
        $columnCheckbox->addColumn(new Columns\MultiSelectCheckbox('id'));
        $this->table->addColumn('', $columnCheckbox, null, new KwField\MultiSelect( '0', ['id' => 'multiselectAll']) );

        $this->table->addOrdering('id', \kalanis\kw_mapper\Interfaces\IQueryBuilder::ORDER_DESC);
        $this->table->addDataSetConnector(new \kalanis\kw_connect\Search\Connector($search));
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
        $ormVideo = $this->table->getDataSetConnector()->getByKey($data[1]);
        $filesizeMB = round(($data[0] / 10), 2);
        return $filesizeMB . ' kB';
    }

    public function getTable(): \kalanis\kw_table\core\Table
    {
        return $this->table;
    }
}
