<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use \Dom\Element;
use VeeWee\Xml\Dom\Collection\NodeList;

/**
 * @return NodeList<\Dom\Element>
 */
function locate_by_namespaced_tag_name(\Dom\Element $node, string $namespace, string $localTagName): NodeList
{
    return NodeList::fromDOMHTMLCollection($node->getElementsByTagNameNS($namespace, $localTagName));
}
