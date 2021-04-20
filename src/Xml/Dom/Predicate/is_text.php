<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMNode;
use DOMText;

/**
 * @psalm-assert-if-true DOMText $node
 */
function is_text(DOMNode $node): bool
{
    return $node instanceof DOMText;
}
