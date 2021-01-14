<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator;

use DOMDocument;
use DOMNode;
use Webmozart\Assert\Assert;

function import_node_deeply(DOMNode $source, DOMNode $target): DOMNode
{
    $document = $target instanceof DOMDocument ? $target : $target->ownerDocument;
    Assert::notNull($document);

    $result = @$document->importNode($source, true);
    Assert::notFalse($result, 'Cannot import node: Node Type Not Supported');

    return $result;
}
