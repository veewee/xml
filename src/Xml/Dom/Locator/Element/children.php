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
function children(DOMNode $node): NodeList
{
    /** @var list<DOMElement> $children */
    $children = filter(
        $node->childNodes,
        static fn (DOMNode $node): bool => is_element($node)
    );

    return new NodeList(...$children);
}
