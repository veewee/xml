<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\Attr;
use Dom\Node;

/**
 * @psalm-assert-if-true Attr $node
 */
function is_attribute(Node $node): bool
{
    return $node instanceof Attr;
}
