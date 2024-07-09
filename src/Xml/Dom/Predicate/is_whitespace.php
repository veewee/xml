<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\Node;

function is_whitespace(\Dom\Node $node): bool
{
    return is_text($node) && trim($node->nodeValue ?? '') === '';
}
