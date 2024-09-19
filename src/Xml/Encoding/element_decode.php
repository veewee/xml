<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use Dom\Element;
use Dom\XMLDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Encoding\Internal\Decoder\Builder\element;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param list<callable(XMLDocument): XMLDocument> $configurators
 *
 * @throws EncodingException
 */
function element_decode(Element $element, callable ... $configurators): array
{
    return wrap_exception(
        static function () use ($element, $configurators): array {
            $doc = Document::fromXmlNode($element, ...$configurators);
            $root = $doc->locate(document_element());

            return element($root);
        }
    );
}
