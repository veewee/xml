<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMElement;
use DOMNode;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @psalm-assert-if-true DOMElement $node
 */
function is_document_element(DOMNode $node): bool
{
    return is_element($node) && detect_document($node)->documentElement === $node;
}
