<?php

namespace kalanis\kw_table\Table\Rules;


use kalanis\kw_mapper\MapperException;
use kalanis\kw_table\Interfaces\Connector\IConnector;
use kalanis\kw_table\Interfaces\Table\IRow;
use kalanis\kw_table\Interfaces\Table\IRule;
use kalanis\kw_table\TableException;


/**
 * Class DataSourceSet
 * @package kalanis\kw_table\Table\Rules
 * Check item in datasource against multiple rules
 */
class DataSourceSet implements IRule
{
    /** @var IConnector */
    protected $dataSource = null;
    /** @var mixed[string, IRule] key on orm , rule itself */
    protected $rules = [];
    protected $any = true;

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

    public function mustPass($all = false)
    {
        $this->any = !(bool)$all;
        return $this;
    }

    /**
     * Check each item
     * @param string $value key to get data object in source
     * @return bool
     * @throws TableException
     * @throws MapperException
     *
     * It is not defined what came from datasource, so for that it has check
     */
    public function validate($value = 'id'): bool
    {
        $trueCount = 0;
        $data = $this->dataSource->getByKey($value);

        foreach ($this->rules as list($key, $rule)) {
            /** @var IRule $rule */
            $checkValue = is_object($data)
                ? ($data instanceof IRow ? $data->getValue($key) : $data->$key)
                : (is_array($data) ? $data[$key] : null );

            if ($rule->validate($checkValue)) {
                $trueCount++;
            }
        }

        if ((true == $this->any) && (0 < $trueCount)) {
            return true;
        }

        if (count($this->rules) == $trueCount) {
            return true;
        }

        return false;
    }
}
