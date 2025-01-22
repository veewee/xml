<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\Node;
use function is_array;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @param list<callable(Node): (list<Node>|Node)> $builders
 *
 * @return Closure(Node): list<Node>
 */
function nodes(callable ... $builders): Closure
{
    return
        /**
         * @return list<Node>
         */
        static fn (Node $node): array
            => reduce(
                $builders,
                /**
                 * @param list<Node> $builds
                 * @param callable(Node): (Node|list<Node>) $builder
                 * @return list<Node>
                 */
                static function (array $builds, callable $builder) use ($node): array {
                    $result = $builder(detect_document($node));
                    $newBuilds = is_array($result) ? $result : [$result];

                    return [...$builds, ...$newBuilds];
                },
                []
            );
}
