<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use Psl\Type\Exception\CoercionException;
use Psl\Type\TypeInterface;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @template T
 *
 * @psalm-param TypeInterface<T> $type
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 *
 * @return T
 *
 * @throws CoercionException
 * @throws RuntimeException
 */
function typed(string $xml, TypeInterface $type, callable ... $configurators)
{
    return $type->coerce(decode($xml, ...$configurators));
}
