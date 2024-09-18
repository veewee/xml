<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xmlns;

use Dom\Element;
use Dom\NamespaceInfo;

/**
 * @return list<NamespaceInfo>
 */
function recursive_linked_namespaces(Element $node): array
{
    return $node->getDescendantNamespaces();
}
