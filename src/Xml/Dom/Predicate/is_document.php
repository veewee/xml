<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMDocument;
use DOMNode;

/**
 * @psalm-assert-if-true DOMDocument $node
 */
function is_document(DOMNode $node): bool
{
    return $node instanceof DOMDocument;
}
