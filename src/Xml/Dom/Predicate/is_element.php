<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\Element;
use \DOM\Node;

/**
 * @psalm-assert-if-true \DOM\Element $node
 */
function is_element(\DOM\Node $node): bool
{
    return $node instanceof \DOM\Element;
}
