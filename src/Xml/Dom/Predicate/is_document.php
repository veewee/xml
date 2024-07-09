<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\XMLDocument;
use \Dom\Node;

/**
 * @psalm-assert-if-true \Dom\XMLDocument $node
 */
function is_document(\Dom\Node $node): bool
{
    return $node instanceof \Dom\XMLDocument;
}
