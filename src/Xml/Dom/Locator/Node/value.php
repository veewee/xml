<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMNode;
use Psl\Type\Exception\CoercionException;
use Psl\Type\TypeInterface;

/**
 * @template T
 *
 * @param TypeInterface<T> $type
 *
 * @return T
 *
 * @throws CoercionException
 */
function value(DOMNode $node, TypeInterface $type)
{
    return $type->coerce($node->nodeValue);
}
