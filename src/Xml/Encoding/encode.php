<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use Psl\Type\Exception\AssertException;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\append;
use function VeeWee\Xml\Encoding\Internal\Encoder\Builder\root;

/**
 * @param list<callable(DOMDocument): DOMDocument> $configurators
 *
 * @throws RuntimeException
 * @throws EncodingException
 * @throws AssertException
 */
function encode(array $data, callable ... $configurators): string
{
    $doc = Document::configure(...$configurators);
    $doc->manipulate(
        append(
            ...$doc->build(root($data))
        )
    );

    return $doc->toXmlString();
}
