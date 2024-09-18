<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use Dom\Element;
use Dom\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\filter;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<Element>
 */
function siblings(Node $node): NodeList
{
    /** @var NodeList<Element> $siblings */
    $siblings = new NodeList(...filter(
        $node->parentNode?->childNodes?->getIterator() ?? [],
        static fn (Node $sibling): bool => is_element($sibling) && $sibling !== $node
    ));

    return $siblings;
}
