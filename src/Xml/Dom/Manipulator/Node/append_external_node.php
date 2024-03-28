<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use \DOM\Node;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @throws RuntimeException
 */
function append_external_node(\DOM\Node $target, \DOM\Node $source): \DOM\Node
{
    $copy = import_node_deeply($target, $source);
    $target->appendChild($copy);

    return $copy;
}
