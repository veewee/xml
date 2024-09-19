<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use Dom\XMLDocument;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param list<callable(XMLDocument): XMLDocument> $configurators
 *
 * @throws EncodingException
 */
function xml_encode(array $data, callable ... $configurators): string
{
    return wrap_exception(
        static function () use ($data, $configurators): string {
            return document_encode($data, ...$configurators)->toXmlString();
        }
    );
}
