<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use \Dom\Element;
use \Dom\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\filter;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\Dom\Element>
 */
function siblings(\Dom\Node $node): NodeList
{
    /** @var NodeList<\Dom\Element> $siblings */
    $siblings = new NodeList(...filter(
        $node->parentNode?->childNodes?->getIterator() ?? [],
        static fn (\Dom\Node $sibling): bool => is_element($sibling) && $sibling !== $node
    ));

    return $siblings;
}
