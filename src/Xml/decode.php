<?php

declare(strict_types=1);

namespace VeeWee\Xml;

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Internal\Decoder\Context;
use function VeeWee\Xml\Encoding\Internal\Decoder\Parser\element;

function decode(string $xml): array
{
    $doc = Document::fromXmlString($xml);
    $context = Context::fromDocument($doc);
    $root = $doc->toUnsafeDocument()->documentElement;

    return element($root, $context);
}
