<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMNode;

/**
 * @param list<callable(DOMNode): DOMNode> $builders
 *
 * @return callable(DOMNode): DOMNode
 */
function children(callable ...$builders): callable
{
    return static function (DOMNode $node) use ($builders): DOMNode {
        foreach ($builders as $builder) {
            $node->appendChild($builder($node));
        }

        return $node;
    };
}
