<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use Closure;
use DOMDocument;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param list<\Closure(DOMDocument): DOMDocument> $configurators
 *
 * @throws EncodingException
 */
function xml_encode(array $data, Closure ... $configurators): string
{
    return wrap_exception(
        static function () use ($data, $configurators): string {
            return document_encode($data, ...$configurators)->toXmlString();
        }
    );
}
