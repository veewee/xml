<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\Node;

function is_default_xmlns_attribute(\DOM\Node $node): bool
{
    return is_xmlns_attribute($node) && $node->prefix === null;
}
