<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Namespaces;

use DOMNameSpaceNode;
use DOMNode;
use DOMNodeList;
use InvalidArgumentException;
use VeeWee\Xml\Dom\Xpath;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @psalm-suppress MixedReturnTypeCoercion
 * @return DOMNodeList<DOMNameSpaceNode>
 *
 * @throws RuntimeException
 * @throws InvalidArgumentException
 */
function linked_namespaces(DOMNode $node): DOMNodeList
{
    $xpath = Xpath::fromUnsafeNode($node);

    return $xpath->query('namespace::*', $node);
}
