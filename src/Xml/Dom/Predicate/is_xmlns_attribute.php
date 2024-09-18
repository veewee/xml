<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\Attr;
use Dom\Node;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @psalm-assert-if-true Attr $node
 */
function is_xmlns_attribute(Node $node): bool
{
    return is_attribute($node) && $node->namespaceURI === Xmlns::xmlns()->value();
}
