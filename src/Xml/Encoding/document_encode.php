<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function VeeWee\Xml\Encoding\Internal\Encoder\Builder\normalize_data;
use function VeeWee\Xml\Encoding\Internal\Encoder\Builder\root;
use function VeeWee\Xml\Encoding\Internal\wrap_exception;

/**
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 *
 * @throws EncodingException
 */
function document_encode(array $data, callable ... $configurators): Document
{
    return wrap_exception(
        static function () use ($data, $configurators): Document {
            $doc = Document::configure(...$configurators);
            $doc->build(
                root(
                    normalize_data($data)
                )
            );

            return $doc;
        }
    );
}
