<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMNode;
use Psl\Type\Type;

/**
 * @template T
 *
 * @param Type<T> $type
 *
 * @return T
 */
function value(DOMNode $node, Type $type): T
{
    return $type->coerce($node->nodeValue);
}
