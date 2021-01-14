<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator;

use DOMDocument;
use DOMNode;
use Webmozart\Assert\Assert;
use function Psl\Fun\identity;
use function Psl\Fun\rethrow;
use function VeeWee\Xml\ErrorHandling\detect_warnings;

function import_node_deeply(DOMNode $source, DOMNode $target): DOMNode
{
    $document = $target instanceof DOMDocument ? $target : $target->ownerDocument;
    Assert::notNull($document);

    $result = detect_warnings(fn() => $document->importNode($source, true))->proceed(
        identity(),
        rethrow()
    );

    Assert::notFalse($result, 'Cannot import node: Node Type Not Supported');

    return $result;
}
