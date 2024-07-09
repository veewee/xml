<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \Dom\XMLDocument;
use \Dom\Node;
use function is_array;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @param list<callable(\Dom\XMLDocument): (list<\Dom\Node>|\Dom\Node)> $builders
 *
 * @return Closure(\Dom\XMLDocument): list<\Dom\Node>
 */
function nodes(callable ... $builders): Closure
{
    return
        /**
         * @return list<\Dom\Node>
         */
        static fn (\Dom\Node $node): array
            => reduce(
                $builders,
                /**
                 * @param list<\Dom\Node> $builds
                 * @param callable(\Dom\XMLDocument): (\Dom\Node|list<\Dom\Node>) $builder
                 * @return list<\Dom\Node>
                 */
                static function (array $builds, callable $builder) use ($node): array {
                    $result = $builder(detect_document($node));
                    $newBuilds = is_array($result) ? $result : [$result];

                    return [...$builds, ...$newBuilds];
                },
                []
            );
}
