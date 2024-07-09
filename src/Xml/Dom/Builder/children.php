<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\Node;

/**
 * @template T of \Dom\Node
 *
 * @param list<callable(T): \Dom\Node> $builders
 *
 * @return Closure(T): T
 */
function children(callable ...$builders): Closure
{
    return static function (\Dom\Node $node) use ($builders): \Dom\Node {
        foreach ($builders as $builder) {
            $node->appendChild($builder($node));
        }

        return $node;
    };
}
