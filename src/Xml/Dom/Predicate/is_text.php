<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\Node;
use \DOM\Text;

/**
 * @psalm-assert-if-true \DOM\Text $node
 */
function is_text(\DOM\Node $node): bool
{
    return $node instanceof \DOM\Text;
}
