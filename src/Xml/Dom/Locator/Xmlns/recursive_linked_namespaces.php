<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xmlns;

use \DOM\NameSpaceNode;
use \DOM\Node;
use InvalidArgumentException;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Dom\Xpath;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @return NodeList<\DOM\NameSpaceNode>
 *
 * @throws RuntimeException
 * @throws InvalidArgumentException
 */
function recursive_linked_namespaces(\DOM\Node $node): NodeList
{
    $xpath = Xpath::fromUnsafeNode($node);

    return $xpath->query('.//namespace::*', $node)->expectAllOfType(\DOM\NameSpaceNode::class);
}
