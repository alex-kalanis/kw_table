<?php

namespace kalanis\kw_table\Connector;


use kalanis\kw_pager\Interfaces\IPager;
use kalanis\kw_paging\Interfaces\ILink;
use kalanis\kw_paging\Positions;
use kalanis\kw_table\UrlHandler\UrlVariable;


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
$urlLink = new PageLink(new UrlVariable(new UrlHandler($inputs)), $pager, 'paging');

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

    /** @var UrlVariable */
    protected $urlVariable;
    /** @var IPager */
    protected $pager;
    /** @var string */
    protected $varName = self::DEFAULT_VAR_NAME;

    public function __construct(UrlVariable $urlVariable, IPager $pager, string $variableName = self::DEFAULT_VAR_NAME)
    {
        $urlVariable->setVariableName($variableName);
        $urlVariable->setVariableValue(Positions::FIRST_PAGE);
        $this->urlVariable = $urlVariable;
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
        return $this->urlVariable->getUrl();
    }
}
