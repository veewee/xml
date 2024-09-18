<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\Element;
use Dom\Node;

/**
 * @psalm-assert-if-true Element $node
 */
function is_element(Node $node): bool
{
    return $node instanceof Element;
}
