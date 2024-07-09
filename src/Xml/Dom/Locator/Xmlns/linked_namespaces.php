<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xmlns;

/**
 * @return list<\Dom\NamespaceInfo>
 */
function linked_namespaces(\Dom\Element $node): array
{
    return $node->getInScopeNamespaces();
}
