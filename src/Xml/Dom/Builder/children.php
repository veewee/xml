<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMNode;

/**
 * @template T of DOMNode
 *
 * @param list<callable(T): DOMNode> $builders
 *
 * @return Closure(T): T
 */
function children(callable ...$builders): Closure
{
    return static function (DOMNode $node) use ($builders): DOMNode {
        foreach ($builders as $builder) {
            $node->appendChild($builder($node));
        }

        return $node;
    };
}
