<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use DOMElement;
use DOMNodeList;

/**
 * @return DOMNodeList<DOMElement>
 */
function locate_by_tag_name(DOMElement $node, string $tag): DOMNodeList
{
    return $node->getElementsByTagName($tag);
}
