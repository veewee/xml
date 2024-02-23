<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use \DOM\Node;
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
function value(\DOM\Node $node, TypeInterface $type)
{
    // TODO : nodeValue did entity substitution
    // TODO : nodeValue returns null for elements
    // TODO : How to best deal with this?
    return $type->coerce($node->textContent ?? '');
}
