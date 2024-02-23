<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\Node;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @psalm-assert-if-true \DOM\Attr $node
 */
function is_xmlns_attribute(\DOM\Node $node): bool
{
    return is_attribute($node) && $node->namespaceURI === Xmlns::xmlns()->value();
}
