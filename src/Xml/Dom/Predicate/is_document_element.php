<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMNameSpaceNode;
use DOMNode;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

function is_document_element(DOMNode|DOMNameSpaceNode $node): bool
{
    return is_element($node) && detect_document($node)->documentElement === $node;
}
