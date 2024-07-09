<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\Node;

function is_default_xmlns_attribute(\Dom\Node $node): bool
{
    return is_xmlns_attribute($node) && $node->prefix === null;
}
