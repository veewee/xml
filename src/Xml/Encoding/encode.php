<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Manipulator\append;
use function VeeWee\Xml\Encoding\Internal\Encoder\Builder\root;

/**
 * @param list<callable(DOMDocument): DOMDocument> $configurators
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
