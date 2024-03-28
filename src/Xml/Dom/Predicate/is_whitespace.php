<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\Node;

function is_whitespace(\DOM\Node $node): bool
{
    return is_text($node) && trim($node->nodeValue ?? '') === '';
}
