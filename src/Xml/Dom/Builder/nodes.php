<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\XMLDocument;
use \DOM\Node;
use function is_array;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @param list<callable(\DOM\XMLDocument): (list<\DOM\Node>|\DOM\Node)> $builders
 *
 * @return Closure(\DOM\XMLDocument): list<\DOM\Node>
 */
function nodes(callable ... $builders): Closure
{
    return
        /**
         * @return list<\DOM\Node>
         */
        static fn (\DOM\Node $node): array
            => reduce(
                $builders,
                /**
                 * @param list<\DOM\Node> $builds
                 * @param callable(\DOM\XMLDocument): (\DOM\Node|list<\DOM\Node>) $builder
                 * @return list<\DOM\Node>
                 */
                static function (array $builds, callable $builder) use ($node): array {
                    $result = $builder(detect_document($node));
                    $newBuilds = is_array($result) ? $result : [$result];

                    return [...$builds, ...$newBuilds];
                },
                []
            );
}
