<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IConnector;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Table\IRule;
use kalanis\kw_table\core\TableException;


/**
 * Class DataSourceSet
 * @package kalanis\kw_table\core\Table\Rules
 * Check item in data source against multiple rules
 */
class DataSourceSet implements IRule
{
    /** @var IConnector */
    protected $dataSource = null;
    /** @var mixed[string, IRule] key on orm , rule itself */
    protected $rules = [];
    protected $all = true;

    public function setDataSource(IConnector $dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    public function addRule(IRule $rule, $key)
    {
        $this->rules[] = [$key, $rule];
        return $this;
    }

    public function allMustPass($all = true)
    {
        $this->all = (bool)$all;
        return $this;
    }

    /**
     * Check each item
     * @param string|int $value key to get data object in source
     * @return bool
     * @throws TableException
     * @throws ConnectException
     *
     * It is not defined what came from the data source, so for that it has check
     */
    public function validate($value = '0'): bool
    {
        $trueCount = 0;
        $data = $this->dataSource->getByKey($value);

        foreach ($this->rules as list($key, $rule)) {
            /** @var IRule $rule */
            if ($rule->validate($this->valueToCheck($data, $key))) {
                $trueCount++;
            }
        }

        if ((false == $this->all) && (0 < $trueCount)) {
            return true;
        }

        if (count($this->rules) == $trueCount) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed $data
     * @param string|int $key
     * @return mixed|null
     * @throws ConnectException
     */
    protected function valueToCheck($data, $key)
    {
        return is_object($data)
            ? ($data instanceof IRow ? $data->getValue($key) : $data->$key)
            : (is_array($data) ? $data[$key] : null );
    }
}
