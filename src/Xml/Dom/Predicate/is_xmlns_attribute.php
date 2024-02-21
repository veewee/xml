<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\NameSpaceNode;
use \DOM\Node;

/**
 * @psalm-assert-if-true \DOM\NameSpaceNode $node
 */
function is_xmlns_attribute(\DOM\Node|\DOM\NameSpaceNode $node): bool
{
    return $node instanceof \DOM\NameSpaceNode;
}
