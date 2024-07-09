<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\Attr;
use \Dom\Node;

/**
 * @psalm-assert-if-true \Dom\Attr $node
 */
function is_attribute(\Dom\Node $node): bool
{
    return $node instanceof \Dom\Attr;
}
