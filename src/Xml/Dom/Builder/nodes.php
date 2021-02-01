<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMDocument;
use DOMNode;
use function Psl\Iter\reduce;
use function Psl\Type\is_array;

/**
 * @param list<callable(DOMDocument): (list<DOMNode>|DOMNode)> $builders
 *
 * @return callable(DOMDocument): list<DOMNode>
 */
function nodes(callable ... $builders): callable
{
    return
        /**
         * @return list<DOMNode>
         */
        static fn (DOMDocument $document): array
            => reduce(
                $builders,
                /**
                 * @param list<DOMNode> $builds
                 * @param callable(DOMDocument): (DOMNode|list<DOMNode>) $builder
                 * @return list<DOMNode>
                 */
                static function (array $builds, callable $builder) use ($document): array {
                    $result = $builder($document);
                    $newBuilds = is_array($result) ? $result : [$result];

                    return [...$builds, $newBuilds];
                },
                []
            );
}
