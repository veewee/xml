<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use DOMElement;
use DOMNodeList;

/**
 * @return DOMNodeList<DOMElement>
 */
function locate_by_namespaced_tag_name(DOMElement $node, string $namespace, string $localTagName): DOMNodeList
{
    return $node->getElementsByTagNameNS($namespace, $localTagName);
}
