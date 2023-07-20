<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMCdataSection;
use DOMNode;

/**
 * @psalm-assert-if-true DOMCdataSection $node
 */
function is_cdata(DOMNode $node): bool
{
    return $node instanceof DOMCdataSection;
}
