<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Node;

/**
 * @template T of \DOM\Node
 *
 * @param list<callable(T): \DOM\Node> $builders
 *
 * @return Closure(T): T
 */
function children(callable ...$builders): Closure
{
    return static function (\DOM\Node $node) use ($builders): \DOM\Node {
        foreach ($builders as $builder) {
            $node->appendChild($builder($node));
        }

        return $node;
    };
}
