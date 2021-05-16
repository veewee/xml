<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMNode;
use VeeWee\Xml\Dom\Collection\NodeList;

/**
 * @return NodeList<DOMNode>
 */
function children(DOMNode $node): NodeList
{
    return NodeList::fromDOMNodeList($node->childNodes);
}
