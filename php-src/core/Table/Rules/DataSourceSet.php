<?php

namespace kalanis\kw_table\core\Table\Rules;


use kalanis\kw_connect\core\ConnectException;
use kalanis\kw_connect\core\Interfaces\IConnector;
use kalanis\kw_connect\core\Interfaces\IRow;
use kalanis\kw_table\core\Interfaces\Table\IRule;
use kalanis\kw_table\core\Table\Internal\Attributes;
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
    /** @var array<Attributes> */
    protected $rules = [];
    /** @var bool */
    protected $all = true;

    public function setDataSource(IConnector $dataSource): self
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @param IRule $rule
     * @param string|int $key
     * @return $this
     */
    public function addRule(IRule $rule, $key): self
    {
        $this->rules[] = new Attributes($key, '', $rule);
        return $this;
    }

    /**
     * @param bool $all
     * @return $this
     */
    public function allMustPass($all = true): self
    {
        $this->all = (bool) $all;
        return $this;
    }

    /**
     * Check each item
     * @param string|int $value key to get data object in source
     * @throws ConnectException
     * @throws TableException
     * @return bool
     *
     * It is not defined what came from the data source, so for that it has check
     */
    public function validate($value = '0'): bool
    {
        $trueCount = 0;
        $data = $this->dataSource->getByKey($value);

        foreach ($this->rules as $attr) {
            /** @var Attributes $attr */
            if ($attr->getCondition()->validate($this->valueToCheck($data, $attr->getColumnName()))) {
                $trueCount++;
            }
        }

        if ((false === $this->all) && (0 < $trueCount)) {
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
     * @throws ConnectException
     * @return mixed|null
     */
    protected function valueToCheck($data, $key)
    {
        return is_object($data)
            ? ($data instanceof IRow ? $data->getValue($key) : $data->$key)
            : (is_array($data) ? $data[$key] : null );
    }
}
