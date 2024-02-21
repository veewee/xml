<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use \DOM\Attr;
use \DOM\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\DOM\Attr>
 */
function attributes_list(\DOM\Node $node): NodeList
{
    if (!is_element($node)) {
        return NodeList::empty();
    }

    $attributes = values($node->attributes);

    return new NodeList(...$attributes);
}
