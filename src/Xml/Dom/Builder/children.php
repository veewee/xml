<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Node;

/**
 * @template T of Node
 *
 * @param list<callable(T): Node> $builders
 *
 * @return Closure(T): T
 */
function children(callable ...$builders): Closure
{
    return static function (Node $node) use ($builders): Node {
        foreach ($builders as $builder) {
            $node->appendChild($builder($node));
        }

        return $node;
    };
}
