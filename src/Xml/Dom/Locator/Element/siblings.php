<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use \DOM\Element;
use \DOM\Node;
use VeeWee\Xml\Dom\Collection\NodeList;
use function Psl\Vec\filter;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\DOM\Element>
 */
function siblings(\DOM\Node $node): NodeList
{
    /** @var NodeList<\DOM\Element> $siblings */
    $siblings = new NodeList(...filter(
        $node->parentNode?->childNodes?->getIterator() ?? [],
        static fn (\DOM\Node $sibling): bool => is_element($sibling) && $sibling !== $node
    ));

    return $siblings;
}
