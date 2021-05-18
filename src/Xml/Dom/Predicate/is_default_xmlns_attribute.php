<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use DOMNameSpaceNode;
use DOMNode;

function is_default_xmlns_attribute(DOMNode|DOMNameSpaceNode $node): bool
{
    return is_xmlns_attribute($node) && $node->prefix === '';
}
