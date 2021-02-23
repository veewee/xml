<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

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
use function VeeWee\Xml\Dom\Builder\children as buildChildren;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\escaped_value;
use function VeeWee\Xml\Dom\Builder\namespaced_element as namespacedElementBuilder;

function element(string $name, array $data): callable
{
    $nullableMap = union(dict(string(), string()), null());
    $attributes = $nullableMap->assert($data['@attributes'] ?? null);
    $namespaces = $nullableMap->assert($data['@namespaces'] ?? null);
    $value = union(string(), null())->assert($data['@value'] ?? null);

    $element = filter_keys(
        $data,
        static fn ($key): bool => !in_array($key, ['@attributes', '@namespaces', '@value'], true)
    );

    $currentNamespace = $namespaces[''] ?? null;
    $namedNamespaces = reduce_with_keys(
        filter_keys($namespaces ?? []),
        static fn (array $namespaces, string $prefix, string $namespace)
            => merge($namespaces, ['xmlns:'.$prefix => $namespace]),
        []
    );

    $buildTerminatedChild = static fn(string $name, string $value)
        => buildChildren(elementBuilder($name, escaped_value($value)));
    $buildNestedChild = static fn(string $name, array $value)
        => is_node_list($value)
            ? children($name, $value)
            : buildChildren(element($name, $value));

    $buildChildElement = static fn(string $name, string|array $value)
        => is_string($value)
            ? $buildTerminatedChild($name, $value)
            : $buildNestedChild($name, $value);

    $children = filter_nulls([
        $attributes ? attributes($attributes) : null,
        $namedNamespaces ? attributes($namedNamespaces) : null,
        $value ? escaped_value($value) : null,
        ...values(map_with_key($element, $buildChildElement)),
    ]);

    return $currentNamespace
        ? namespacedElementBuilder($currentNamespace, $name, ...$children)
        : elementBuilder($name, ...$children);
}
