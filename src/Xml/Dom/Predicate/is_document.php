<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\XMLDocument;
use \DOM\Node;

/**
 * @psalm-assert-if-true \DOM\XMLDocument $node
 */
function is_document(\DOM\Node $node): bool
{
    return $node instanceof \DOM\XMLDocument;
}
