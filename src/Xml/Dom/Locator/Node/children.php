<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use \Dom\Node;
use VeeWee\Xml\Dom\Collection\NodeList;

/**
 * @return NodeList<\Dom\Node>
 */
function children(\Dom\Node $node): NodeList
{
    return NodeList::fromDOMNodeList($node->childNodes);
}
