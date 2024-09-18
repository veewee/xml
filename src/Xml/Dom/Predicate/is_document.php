<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\Node;
use Dom\XMLDocument;

/**
 * @psalm-assert-if-true XMLDocument $node
 */
function is_document(Node $node): bool
{
    return $node instanceof XMLDocument;
}
