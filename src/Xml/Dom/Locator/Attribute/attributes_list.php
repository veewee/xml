<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use DOMAttr;
use DOMNode;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<DOMAttr>
 */
function attributes_list(DOMNode $node): NodeList
{
    if (!is_element($node)) {
        return NodeList::empty();
    }

    $attributes = values($node->attributes);

    return new NodeList(...$attributes);
}
