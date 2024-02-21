<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Attribute;

use \DOM\NameSpaceNode;
use \DOM\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Xmlns\linked_namespaces;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\DOM\NameSpaceNode>
 * @throws RuntimeException
 */
function xmlns_attributes_list(\DOM\Node $node): NodeList
{
    if (! is_element($node)) {
        return NodeList::empty();
    }

    return linked_namespaces($node)
        ->filter(static fn (\DOM\NameSpaceNode $namespace): bool => $node->hasAttribute($namespace->nodeName));
}
