<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMNode;
use Psl\Type\Exception\CoercionException;
use Psl\Type\Type;

/**
 * @template T
 *
 * @param Type<T> $type
 *
 * @return T
 *
 * @throws CoercionException
 */
function value(DOMNode $node, Type $type)
{
    return $type->coerce($node->nodeValue);
}
