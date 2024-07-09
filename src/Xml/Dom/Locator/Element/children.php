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
function children(\Dom\Node $node): NodeList
{
    /** @var list<\Dom\Element> $children */
    $children = filter(
        $node->childNodes,
        static fn (\Dom\Node $node): bool => is_element($node)
    );

    return new NodeList(...$children);
}
