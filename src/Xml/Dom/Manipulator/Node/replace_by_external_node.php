<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMNode;
use function get_class;
use Webmozart\Assert\Assert;

function replace_by_external_node(DOMNode $target, DOMNode $source): DOMNode
{
    Assert::notNull($target->parentNode, 'Could not replace a node without parent node. ('.get_class($target).')');

    $copy = import_node_deeply($target, $source);
    $oldNode = $target->parentNode->replaceChild($copy, $target);
    Assert::notFalse($oldNode, 'Could not replace the child node.');

    return $copy;
}
