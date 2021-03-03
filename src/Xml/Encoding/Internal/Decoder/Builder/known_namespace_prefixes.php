<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Decoder\Builder;

use DOMDocument;
use DOMNameSpaceNode;
use InvalidArgumentException;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use function Psl\Dict\filter_with_key;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\Namespaces\recursive_linked_namespaces;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 *
 * @return array<string, Xmlns>
 *
 * @throws RuntimeException
 * @psalm-suppress MissingThrowsDocblock - InvalidArgumentException won't be thrown!
 */
function known_namespace_prefixes(DOMDocument $document): array
{
    return filter_with_key(
        reduce(
            recursive_linked_namespaces($document),
            /**
             * @param array<string, Xmlns> $map
             */
            static fn (array $map, DOMNameSpaceNode $node): array
                => merge($map, [$node->localName => Xmlns::load($node->namespaceURI)]),
            []
        ),
        static fn (string $key, Xmlns $namespace): bool =>
            $key !== 'xmlns'
            && !$namespace->matches(Xmlns::xml())
    );
}
