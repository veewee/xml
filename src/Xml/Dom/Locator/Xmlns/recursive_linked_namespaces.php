<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xmlns;

use DOMNameSpaceNode;
use DOMNode;
use InvalidArgumentException;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Dom\Xpath;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @return NodeList<DOMNameSpaceNode>
 *
 * @throws RuntimeException
 * @throws InvalidArgumentException
 */
function recursive_linked_namespaces(DOMNode $node): NodeList
{
    $xpath = Xpath::fromUnsafeNode($node);

    return $xpath->query('.//namespace::*', $node)->expectAllOfType(DOMNameSpaceNode::class);
}
