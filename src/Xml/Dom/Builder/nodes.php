<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMDocument;
use DOMNode;
use function is_array;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @param list<\Closure(DOMDocument): (list<DOMNode>|DOMNode)> $builders
 *
 * @return \Closure(DOMDocument): list<DOMNode>
 */
function nodes(Closure ... $builders): Closure
{
    return
        /**
         * @return list<DOMNode>
         */
        static fn (DOMNode $node): array
            => reduce(
                $builders,
                /**
                 * @param list<DOMNode> $builds
                 * @param \Closure(DOMDocument): (DOMNode|list<DOMNode>) $builder
                 * @return list<DOMNode>
                 */
                static function (array $builds, Closure $builder) use ($node): array {
                    $result = $builder(detect_document($node));
                    $newBuilds = is_array($result) ? $result : [$result];

                    return [...$builds, ...$newBuilds];
                },
                []
            );
}
