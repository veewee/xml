<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMNameSpaceNode;
use DOMNode;

/**
 * @psalm-assert-if-true DOMNameSpaceNode $node
 */
function is_xmlns_attribute(DOMNode|DOMNameSpaceNode $node): bool
{
    return $node instanceof DOMNameSpaceNode;
}
