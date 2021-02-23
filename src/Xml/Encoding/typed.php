<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use Psl\Type\Exception\CoercionException;
use Psl\Type\TypeInterface;

/**
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 * @throws CoercionException
 */
function typed(string $xml, TypeInterface $type, callable ... $configurators): array
{
    return $type->coerce(decode($xml, ...$configurators));
}
