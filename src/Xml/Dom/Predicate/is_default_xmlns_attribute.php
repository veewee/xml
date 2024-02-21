<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Predicate;

use \DOM\NameSpaceNode;
use \DOM\Node;

function is_default_xmlns_attribute(\DOM\Node|\DOM\NameSpaceNode $node): bool
{
    return is_xmlns_attribute($node) && $node->prefix === '';
}
