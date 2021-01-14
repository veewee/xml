<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator;

use DOMNode;

function append_external_node(DOMNode $source, DOMNode $target): DOMNode
{
    $copy = import_node_deeply($source, $target);
    $target->appendChild($copy);

    return $copy;
}
