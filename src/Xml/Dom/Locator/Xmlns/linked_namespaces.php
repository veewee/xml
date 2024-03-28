<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xmlns;

/**
 * @return list<\DOM\NamespaceInfo>
 */
function linked_namespaces(\DOM\Element $node): array
{
    return $node->getInScopeNamespaces();
}
