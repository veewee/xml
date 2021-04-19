<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use DOMElement;
use VeeWee\Xml\Dom\Collection\NodeList;

/**
 * @return NodeList<DOMElement>
 */
function locate_by_tag_name(DOMElement $node, string $tag): NodeList
{
    return NodeList::fromDOMNodeList($node->getElementsByTagName($tag));
}
