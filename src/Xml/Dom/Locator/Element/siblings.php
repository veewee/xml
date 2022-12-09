<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use DOMElement;
use DOMNode;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\filter;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<DOMElement>
 */
function siblings(DOMNode $node): NodeList
{
    /** @var NodeList<DOMElement> $siblings */
    $siblings = new NodeList(...filter(
        $node->parentNode?->childNodes?->getIterator() ?? [],
        static fn (DOMNode $sibling): bool => is_element($sibling) && $sibling !== $node
    ));

    return $siblings;
}
