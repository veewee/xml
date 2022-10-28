<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMNode;

/**
 * @template T of DOMNode
 *
 * @param list<\Closure(T): DOMNode> $builders
 *
 * @return \Closure(T): T
 */
function children(Closure ...$builders): Closure
{
    return static function (DOMNode $node) use ($builders): DOMNode {
        foreach ($builders as $builder) {
            $node->appendChild($builder($node));
        }

        return $node;
    };
}
