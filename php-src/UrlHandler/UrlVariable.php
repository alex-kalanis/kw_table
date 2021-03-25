<?php

namespace kalanis\kw_table\UrlHandler;


/**
 * Class UrlVariable
 * @package kalanis\kw_table\UrlHandler
 * Process single defined parameter in URL inside the handler
 */
class UrlVariable
{
    /** @var UrlHandler */
    protected $handler = null;
    /** @var string */
    protected $variableValue = '';
    /** @var string */
    protected $variableName = 'variable';

    public function __construct(UrlHandler $handler)
    {
        $this->handler = $handler;
    }

    public function setVariableName(string $name): self
    {
        $this->variableName = $name;
        return $this;
    }

    public function setVariableValue(string $value): self
    {
        $this->handler[$this->variableName] = $value;
        return $this;
    }

    public function getVariableName(): string
    {
        return $this->variableName;
    }

    public function getVariableValue(): string
    {
        return (string)$this->handler[$this->variableName];
    }

    public function getUrl(): string
    {
        return $this->handler->rebuildUrl()->getUrl();
    }
}
