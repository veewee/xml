<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\CDATASection;
use Dom\Node;

/**
 * @psalm-assert-if-true CDATASection $node
 */
function is_cdata(Node $node): bool
{
    return $node instanceof CDATASection;
}
