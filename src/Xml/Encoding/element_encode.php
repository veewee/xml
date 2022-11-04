<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 *
 * @throws EncodingException
 */
function element_encode(array $data, callable ... $configurators): string
{
    return wrap_exception(
        static function () use ($data, $configurators): string {
            $doc = document_encode($data, ...$configurators);
            $root = $doc->locate(document_element());

            return xml_string()($root);
        }
    );
}
