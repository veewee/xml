<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use DOMDocument;
use DOMNode;
use InvalidArgumentException;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\Dom\Predicate\is_document;

/**
 * @throws InvalidArgumentException
 */
function detect_document(DOMNode $node): DOMDocument
{
    $document = is_document($node) ? $node : $node->ownerDocument;
    Assert::notNull($document, 'Expected to find an ownerDocument on provided DOMNode.');

    return $document;
}
