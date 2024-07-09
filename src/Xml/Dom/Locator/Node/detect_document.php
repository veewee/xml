<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Node;

use \Dom\XMLDocument;
use InvalidArgumentException;
use function VeeWee\Xml\Dom\Assert\assert_document;
use function VeeWee\Xml\Dom\Predicate\is_document;

/**
 * @throws InvalidArgumentException
 */
function detect_document(\Dom\Node $node): \Dom\XMLDocument
{
    return is_document($node) ? $node : assert_document($node->ownerDocument);
}
