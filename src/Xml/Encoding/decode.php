<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Internal\Decoder\Context;
use function VeeWee\Xml\Encoding\Internal\Decoder\Builder\element;

/**
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 */
function decode(string $xml, callable ... $configurators): array
{
    $doc = Document::fromXmlString($xml, ...$configurators);
    $context = Context::fromDocument($doc);
    $root = $doc->toUnsafeDocument()->documentElement;

    return element($root, $context);
}
