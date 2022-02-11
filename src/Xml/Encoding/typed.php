<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use Psl\Type\Exception\CoercionException;
use Psl\Type\TypeInterface;
use VeeWee\Xml\Encoding\Exception\EncodingException;

/**
 * @template T
 *
 * @psalm-param non-empty-string $xml
 * @psalm-param TypeInterface<T> $type
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 *
 * @return T
 *
 * @throws CoercionException
 * @throws EncodingException
 */
function typed(string $xml, TypeInterface $type, callable ... $configurators)
{
    return $type->coerce(xml_decode($xml, ...$configurators));
}
