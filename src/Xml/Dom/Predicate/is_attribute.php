<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMAttr;
use DOMNode;

/**
 * @psalm-assert-if-true DOMAttr $node
 */
function is_attribute(DOMNode $node): bool
{
    return $node instanceof DOMAttr;
}
