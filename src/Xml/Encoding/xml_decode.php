<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Encoding\Internal\Decoder\Builder\element;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param non-empty-string $xml
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 *
 * @throws EncodingException
 */
function xml_decode(string $xml, callable ... $configurators): array
{
    return wrap_exception(
        static function () use ($xml, $configurators): array {
            $doc = Document::fromXmlString($xml, ...$configurators);
            $root = $doc->locate(document_element());

            return element($root);
        }
    );
}
