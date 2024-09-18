<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use Dom\Node;
use Dom\Text;

/**
 * @psalm-assert-if-true Text $node
 */
function is_text(Node $node): bool
{
    return $node instanceof Text;
}
