<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMNode;
use function Psl\Arr\map;

/**
 * @template T of DOMNode
 * @param list<callable(T): DOMNode> $applicatives
 *
 * @return callable(T): DOMNode
 */
function apply(callable ... $applicatives): callable
{
    return static function (DOMNode $node) use ($applicatives): array {
        return map(
            $applicatives,
            static fn ($apply): DOMNode => $apply($node)
        );
    };
}
