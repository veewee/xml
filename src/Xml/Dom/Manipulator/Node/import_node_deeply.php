<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMDocument;
use DOMNode;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\ErrorHandling\detect_warnings;

function import_node_deeply(DOMNode $target, DOMNode $source): DOMNode
{
    $document = $target instanceof DOMDocument ? $target : $target->ownerDocument;
    Assert::notNull($document);

    $result = detect_warnings(fn() => $document->importNode($source, true))->getResult();

    Assert::notFalse($result, 'Cannot import node: Node Type Not Supported');

    return $result;
}
