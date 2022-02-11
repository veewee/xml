<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMNode;

function is_non_empty_text(DOMNode $node): bool
{
    return is_text($node) && trim($node->nodeValue ?? '') !== '';
}
