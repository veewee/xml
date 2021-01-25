<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMNode;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @throws RuntimeException
 */
function append_external_node(DOMNode $target, DOMNode $source): DOMNode
{
    $copy = import_node_deeply($target, $source);
    $target->appendChild($copy);

    return $copy;
}
