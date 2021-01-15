<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMNode;
use function Psl\Arr\map;
use function Psl\Arr\values;

/**
 * @template T of DOMNode
 * @param list<callable(T): DOMNode> $applicatives
 *
 * @return callable(T): list<DOMNode>
 */
function apply(callable ... $applicatives): callable
{
    return static function (DOMNode $node) use ($applicatives): array {
        return values(map(
            $applicatives,
            /** @param callable(T): DOMNode $apply */
            static fn (callable $apply): DOMNode => $apply($node)
        ));
    };
}
