<?php

use kalanis\kw_mapper\Interfaces\IDriverSources;
use kalanis\kw_mapper\Interfaces\IEntryType;
use kalanis\kw_mapper\Mappers;
use kalanis\kw_mapper\Records;
use kalanis\kw_mapper\Storage;
use kalanis\kw_table\core\Table\Columns;
use kalanis\kw_table\core\Table\Rules;
use kalanis\kw_table\form_kw\Fields as KwField;

# at first it's necessary to have defined datasource

# somewhere in bootstrap
Storage\Database\ConfigStorage::getInstance()->addConfig(
    Storage\Database\Config::init()->setTarget(
        IDriverSources::TYPE_PDO_SQLITE, 'europe', '127.0.0.1', 8080, 'testing', 'testing', 'users'
    ));

$inputs = new \kalanis\kw_input\Inputs();
$inputs->setSource(new kalanis\kw_input\Sources\Basic());


/**
 * Class Mails
 * @property int id
 * @property string fromMail
 * @property string fromName
 * @property string toMail
 * @property string toName
 * @property int attempt
 * @property string content
 */
class Mails extends Records\ASimpleRecord
{
    const STATUS_OK = 'ok';
    const STATUS_FAIL = 'fail';
    const STATUS_WAIT = 'wait';

    protected function addEntries(): void
    {
        $this->addEntry('id', IEntryType::TYPE_INTEGER, 2048);
        $this->addEntry('fromMail', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('fromName', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('toMail', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('toName', IEntryType::TYPE_STRING, 1024);
        $this->addEntry('attempt', IEntryType::TYPE_INTEGER, 99999999999);
        $this->addEntry('content', IEntryType::TYPE_STRING, PHP_INT_MAX);
        $this->addEntry('status', IEntryType::TYPE_SET, [static::STATUS_WAIT, static::STATUS_OK, static::STATUS_FAIL, ]);
        $this->setMapper('\MailsMapper');
    }
}


class MailsMapper extends Mappers\Database\ADatabase
{
    protected function setMap(): void
    {
        $this->setSource('europe');
        $this->setTable('mails');

        $this->setRelation('id', 'm_id');
        $this->setRelation('fromMail', 'm_from_mail');
        $this->setRelation('fromName', 'm_from_name');
        $this->setRelation('toMail', 'm_to_mail');
        $this->setRelation('toName', 'm_to_name');
        $this->setRelation('attempt', 'm_sent');
        $this->setRelation('content', 'm_content');
        $this->setRelation('status', 'm_status');
        $this->addPrimaryKey('id');
    }
}

# now it's possible to connect search and table engines


/**
 * Class MailTable
 * Example of a bit complicated table with styling and sorting
 */
class MailTable
{
    protected $table = null;

    public function __construct(\kalanis\kw_input\Interfaces\IFiltered $inputs)
    {
        $helper = new \kalanis\kw_table\kw\Helper();
        $helper->fillKwPage($inputs);
        $this->table = $helper->getTable();
    }

    /**
     * @param string $name
     * @throws \kalanis\kw_mapper\MapperException
     * @throws \kalanis\kw_connect\core\ConnectException
     * @throws \kalanis\kw_table\core\TableException
     */
    public function composeTable(string $name = ''): void
    {
        $this->table->setDefaultHeaderFilterFieldAttributes(['style' => 'width:100%']);
        // example of conditioned styling - here by mail status
        $this->table->rowClass('statusCreated', new Rules\Exact(Mails::STATUS_WAIT), 'status');
        $this->table->rowClass('statusCompleted', new Rules\Exact(Mails::STATUS_OK), 'status');
        $this->table->rowClass('statusCrashed', new Rules\Exact(Mails::STATUS_FAIL), 'status');

        // columns
        $this->table->addOrderedColumn('ID', new Columns\Basic('id'), new KwField\TextExact());
        $this->table->addColumn('From', new Columns\Func('id', [$this, 'fromMail']));
        $this->table->addOrderedColumn('To name', new Columns\Bold('toName'), new KwField\TextContains());
        $this->table->addOrderedColumn('To mail', new Columns\Basic('toMail'), new KwField\TextContains());
        $this->table->addOrderedColumn('Attempt', new Columns\Date('attempt', 'Y-m-d H:i:s'));
        $this->table->addColumn('Status', new Columns\Map('status', static::mailStatuses()), new KwField\Options(static::mailStatuses()));

        // sorting and connecting datasource
        $this->table->addOrdering('id',\kalanis\kw_mapper\Interfaces\IQueryBuilder::ORDER_DESC);
        $search = new \kalanis\kw_mapper\Search\Search(new Mails());
        if (!empty($name)) {
            $search->exact('fromMail', $name);
        }
        $this->table->addDataSetConnector(new \kalanis\kw_connect\Search\Connector($search));
    }

    public function fromMail($mailId): string
    {
        /** @var Mails $record */
        $record = $this->table->getDataSetConnector()->getByKey($mailId);
        return sprintf('<%s> %s',
            $record->fromName,
            $record->fromMail
        );
    }

    public static function mailStatuses(): array
    {
        return [
            Mails::STATUS_WAIT => 'Waiting',
            Mails::STATUS_FAIL => 'Crashed',
            Mails::STATUS_OK => 'Sent',
        ];
    }

    public function getTable(): \kalanis\kw_table\core\Table
    {
        return $this->table;
    }
}

# and now... Build and render!

$mt = new MailTable(new \kalanis\kw_input\Filtered\Variables($inputs));
$mt->composeTable();
echo $mt->getTable()->render();