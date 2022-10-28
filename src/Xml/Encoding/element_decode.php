<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use Closure;
use DOMDocument;
use DOMElement;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Encoding\Internal\Decoder\Builder\element;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param list<\Closure(DOMDocument): DOMDocument> $configurators
 *
 * @throws EncodingException
 */
function element_decode(DOMElement $element, Closure ... $configurators): array
{
    return wrap_exception(
        static function () use ($element, $configurators): array {
            $doc = Document::fromXmlNode($element, ...$configurators);
            $root = $doc->locate(document_element());

            return element($root);
        }
    );
}
