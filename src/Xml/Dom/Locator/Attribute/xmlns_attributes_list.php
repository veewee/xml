<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use DOMNameSpaceNode;
use DOMNode;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Xmlns\linked_namespaces;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<DOMNameSpaceNode>
 * @throws RuntimeException
 */
function xmlns_attributes_list(DOMNode $node): NodeList
{
    if (! is_element($node)) {
        return NodeList::empty();
    }

    return linked_namespaces($node)
        ->filter(static fn (DOMNameSpaceNode $namespace): bool => $node->hasAttribute($namespace->nodeName));
}
