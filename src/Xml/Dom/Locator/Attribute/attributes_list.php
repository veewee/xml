<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use \Dom\Attr;
use \Dom\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\Dom\Attr>
 */
function attributes_list(\Dom\Node $node): NodeList
{
    if (!is_element($node)) {
        return NodeList::empty();
    }

    $attributes = values($node->attributes);

    return new NodeList(...$attributes);
}
