<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

/**
 * @return NodeList<\Dom\Attr>
 * @throws RuntimeException
 */
function xmlns_attributes_list(\Dom\Node $node): NodeList
{
    return attributes_list($node)
        ->filter(static fn (\Dom\Attr $attribute): bool => is_xmlns_attribute($attribute));
}
