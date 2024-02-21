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
function children(\DOM\Node $node): NodeList
{
    /** @var list<\DOM\Element> $children */
    $children = filter(
        $node->childNodes,
        static fn (\DOM\Node $node): bool => is_element($node)
    );

    return new NodeList(...$children);
}
