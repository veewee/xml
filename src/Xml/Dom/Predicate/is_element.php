<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMElement;
use DOMNode;

/**
 * @psalm-assert-if-true DOMElement $node
 */
function is_element(DOMNode $node): bool
{
    return $node instanceof DOMElement;
}
