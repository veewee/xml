<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\Node;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @psalm-assert-if-true \Dom\Attr $node
 */
function is_xmlns_attribute(\Dom\Node $node): bool
{
    return is_attribute($node) && $node->namespaceURI === Xmlns::xmlns()->value();
}
