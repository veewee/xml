<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use DOMElement;
use Psl\Type\Exception\AssertException;
use function Psl\Dict\filter_keys;
use function Psl\Dict\map_with_key;
use function Psl\Dict\merge;
use function Psl\Iter\reduce_with_keys;
use function Psl\Type\dict;
use function Psl\Type\null;
use function Psl\Type\string;
use function Psl\Type\union;
use function Psl\Vec\filter_nulls;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Builder\attributes;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\escaped_value;
use function VeeWee\Xml\Dom\Builder\namespaced_element as namespacedElementBuilder;

/**
 * @psalm-suppress LessSpecificReturnStatement, MoreSpecificReturnType
 *
 * @param array<string, string|array> $data
 * @return callable(DOMElement): DOMElement
 *
 * @throws AssertException
 */
function element(string $name, array $data): callable
{
    $nullableMap = union(dict(string(), string()), null());
    $attributes = $nullableMap->assert($data['@attributes'] ?? null);
    $namespaces = $nullableMap->assert($data['@namespaces'] ?? null);
    $value = union(string(), null())->assert($data['@value'] ?? null);

    $element = filter_keys(
        $data,
        static fn (string $key): bool => !in_array($key, ['@attributes', '@namespaces', '@value'], true)
    );

    $currentNamespace = $namespaces[''] ?? null;
    $namedNamespaces = reduce_with_keys(
        filter_keys($namespaces ?? []),
        /**
         * @param array<string, string> $namespaces
         * @return array<string, string>
         */
        static fn (array $namespaces, string $prefix, string $namespace): array
            => merge($namespaces, ['xmlns:'.$prefix => $namespace]),
        []
    );

    $children = filter_nulls([
        $attributes ? attributes($attributes) : null,
        $namedNamespaces ? attributes($namedNamespaces) : null,
        $value ? escaped_value($value) : null,
        ...values(map_with_key(
            $element,
            /**
             * @param string|array<int|string, array|string> $value
             * @return callable(DOMElement): DOMElement
             */
            static fn (string $name, string|array $value): callable
                => parent_node($name, $value)
        )),
    ]);

    return $currentNamespace
        ? namespacedElementBuilder($currentNamespace, $name, ...$children)
        : elementBuilder($name, ...$children);
}
