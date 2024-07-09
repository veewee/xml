<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \Dom\Node;
use \Dom\Text;

/**
 * @psalm-assert-if-true \Dom\Text $node
 */
function is_text(\Dom\Node $node): bool
{
    return $node instanceof \Dom\Text;
}
