<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xmlns;

/**
 * @return list<\Dom\NamespaceInfo>
 */
function recursive_linked_namespaces(\Dom\Element $node): array
{
    return $node->getDescendantNamespaces();
}
