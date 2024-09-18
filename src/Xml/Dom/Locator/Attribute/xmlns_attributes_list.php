<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use Dom\Attr;
use Dom\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

/**
 * @return NodeList<Attr>
 * @throws RuntimeException
 */
function xmlns_attributes_list(Node $node): NodeList
{
    return attributes_list($node)
        ->filter(static fn (Attr $attribute): bool => is_xmlns_attribute($attribute));
}
