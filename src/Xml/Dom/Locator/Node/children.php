<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use \DOM\Node;
use VeeWee\Xml\Dom\Collection\NodeList;

/**
 * @return NodeList<\DOM\Node>
 */
function children(\DOM\Node $node): NodeList
{
    return NodeList::fromDOMNodeList($node->childNodes);
}
