<?php

namespace kalanis\kw_table\Connector;


use kalanis\kw_address_handler\Handler;
use kalanis\kw_address_handler\SingleVariable;
use kalanis\kw_pager\Interfaces\IPager;
use kalanis\kw_paging\Interfaces\ILink;
use kalanis\kw_paging\Positions;


/**
 * Class PageLink
 * @package kalanis\kw_table\Connector
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
    /** @var string */
    protected $varName = self::DEFAULT_VAR_NAME;

    public function __construct(Handler $urlHandler, IPager $pager, string $variableName = self::DEFAULT_VAR_NAME)
    {
        $this->urlHandler = $urlHandler;
        $this->urlVariable = new SingleVariable($urlHandler->getParams());
        $this->urlVariable->setVariableName($variableName);
        $this->urlVariable->setVariableValue(Positions::FIRST_PAGE);
        $this->pager = $pager;
    }

    public function setPageNumber(int $page): void
    {
        $this->pager->setActualPage(
            $this->pager->pageExists($page)
                ? $page
                : ($page > $this->pager->getPagesCount()
                    ? max($this->pager->getPagesCount(), Positions::FIRST_PAGE)
                    : Positions::FIRST_PAGE)
        );
    }

    public function getPageLink(): string
    {
        $this->urlVariable->setVariableValue((string)$this->pager->getActualPage());
        return (string)$this->urlHandler->getAddress();
    }
}
