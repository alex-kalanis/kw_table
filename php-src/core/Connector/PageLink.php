<?php

namespace kalanis\kw_table\core\Connector;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\SingleVariable;
use kalanis\kw_pager\Interfaces\IPager;
use kalanis\kw_paging\Interfaces\ILink;
use kalanis\kw_paging\Positions;


/**
 * Class PageLink
 * @package kalanis\kw_table\core\Connector
 * Update links with data from pager.
 *
 * Example:

$inputs = new kalanis\kw_input\Interfaces\Inputs();

...

$pager = new BasicPager();
$pager->setMaxResults(32)->setLimit(10)->setActualPage(1);
$urlLink = new PageLink(new Handler($inputs), $pager, 'paging');

...

$urlLink->setPageNumber(6);
echo $urlLink->getPageLink(); // got page 1 -> six is too much for this

...

$urlLink->setPageNumber(3);
echo $urlLink->getPageLink(); // got page 3 -> okay

 */
class PageLink implements ILink
{
    const DEFAULT_VAR_NAME = 'page';

    /** @var Handler */
    protected $urlHandler;
    /** @var SingleVariable */
    protected $urlVariable;
    /** @var IPager */
    protected $pager;
    /** @var int */
    protected $page = 0;
    /** @var string */
    protected $varName = self::DEFAULT_VAR_NAME;

    public function __construct(Handler $urlHandler, IPager $pager, string $variableName = self::DEFAULT_VAR_NAME)
    {
        $this->urlHandler = $urlHandler;
        $this->urlVariable = new SingleVariable($urlHandler->getParams());
        $this->urlVariable->setVariableName($variableName);
        $this->page = intval($this->urlVariable->getVariableValue() ?: Positions::FIRST_PAGE);
        $this->urlVariable->setVariableValue(strval($this->page));
        $this->pager = $pager;
    }

    public function setPageNumber(int $page): void
    {
        $this->page = $this->pager->pageExists($page)
                ? $page
                : ($page > $this->pager->getPagesCount()
                    ? max($this->pager->getPagesCount(), Positions::FIRST_PAGE)
                    : Positions::FIRST_PAGE)
        ;
    }

    public function getPageNumber(): int
    {
        return intval($this->page);
    }

    public function getPageLink(): string
    {
        $this->urlVariable->setVariableValue(strval($this->page));
        return strval($this->urlHandler->getAddress());
    }
}
