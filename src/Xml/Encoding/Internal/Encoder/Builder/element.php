<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use Closure;
use DOMElement;
use Psl\Exception\InvariantViolationException;
use Psl\Type\Exception\AssertException;
use function Psl\Dict\filter_keys;
use function Psl\Dict\map_with_key;
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
use function VeeWee\Xml\Dom\Builder\xmlns_attributes;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 * @psalm-suppress LessSpecificReturnStatement, MoreSpecificReturnType
 *
 * @return \Closure(DOMElement): DOMElement
 *
 * @throws AssertException
 * @throws InvariantViolationException
 */
function element(string $name, array $data): Closure
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
    $namedNamespaces = filter_keys($namespaces ?? []);

    $children = filter_nulls([
        $attributes ? attributes($attributes) : null,
        $namedNamespaces ? xmlns_attributes($namedNamespaces) : null,
        $value !== null ? escaped_value($value) : null,
        ...values(map_with_key(
            $element,
            /**
             * @param string|array<int|string, array|string> $value
             * @return \Closure(DOMElement): DOMElement
             */
            static fn (string $name, string|array $value): Closure
                => parent_node($name, $value)
        )),
    ]);

    return $currentNamespace
        ? namespacedElementBuilder($currentNamespace, $name, ...$children)
        : elementBuilder($name, ...$children);
}
