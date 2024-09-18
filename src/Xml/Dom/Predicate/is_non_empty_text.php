<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\Node;

function is_non_empty_text(Node $node): bool
{
    return is_text($node) && trim($node->nodeValue ?? '') !== '';
}
