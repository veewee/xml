<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\Attr;
use \DOM\Node;

/**
 * @psalm-assert-if-true \DOM\Attr $node
 */
function is_attribute(\DOM\Node $node): bool
{
    return $node instanceof \DOM\Attr;
}
