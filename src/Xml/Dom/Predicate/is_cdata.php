<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\CDATASection;
use \Dom\Node;

/**
 * @psalm-assert-if-true \Dom\CDATASection $node
 */
function is_cdata(\Dom\Node $node): bool
{
    return $node instanceof \Dom\CDATASection;
}
