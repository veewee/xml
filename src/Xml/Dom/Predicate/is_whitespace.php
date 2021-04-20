<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMNode;
use DOMText;

/**
 * @psalm-assert-if-true DOMText $node
 */
function is_whitespace(DOMNode $node): bool
{
    return is_text($node) && trim($node->nodeValue) === '';
}
