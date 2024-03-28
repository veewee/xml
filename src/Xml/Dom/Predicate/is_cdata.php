<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\CDATASection;
use \DOM\Node;

/**
 * @psalm-assert-if-true \DOM\CDATASection $node
 */
function is_cdata(\DOM\Node $node): bool
{
    return $node instanceof \DOM\CDATASection;
}
