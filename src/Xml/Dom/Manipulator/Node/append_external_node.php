<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use \Dom\Node;
use VeeWee\Xml\Exception\RuntimeException;

/**
 * @throws RuntimeException
 */
function append_external_node(\Dom\Node $target, \Dom\Node $source): \Dom\Node
{
    $copy = import_node_deeply($target, $source);
    $target->appendChild($copy);

    return $copy;
}
