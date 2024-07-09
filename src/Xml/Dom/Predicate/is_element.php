<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\Element;
use \Dom\Node;

/**
 * @psalm-assert-if-true \Dom\Element $node
 */
function is_element(\Dom\Node $node): bool
{
    return $node instanceof \Dom\Element;
}
