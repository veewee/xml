<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMDocument;
use DOMNode;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * @throws InvalidArgumentException
 */
function detect_document(DOMNode $node): DOMDocument
{
    $document = $node instanceof DOMDocument ? $node : $node->ownerDocument;
    Assert::notNull($document, 'Expected to find an ownerDocument on provided DOMNode.');

    return $document;
}
