<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use \DOM\XMLDocument;
use \DOM\Node;
use InvalidArgumentException;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\Dom\Predicate\is_document;

/**
 * @throws InvalidArgumentException
 * @psalm-suppress RedundantCondition - node->ownerDocument can also be null...
 */
function detect_document(\DOM\Node $node): \DOM\XMLDocument
{
    $document = is_document($node) ? $node : $node->ownerDocument;
    Assert::notNull($document, 'Expected to find an ownerDocument on provided \DOM\Node.');

    return $document;
}
