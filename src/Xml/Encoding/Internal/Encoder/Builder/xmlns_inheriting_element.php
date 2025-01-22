<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use Closure;
use Dom\Element;
use Dom\XMLDocument;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\Dom\Builder\default_xmlns_attribute;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\namespaced_element as namespacedElementBuilder;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\Dom\Predicate\is_prefixed_node_name;

/**
 * This function can create element nodes that inherit the local xmlns namespace of their parent if none is configured.
 *
 * @param list<Closure(Element): Element> $children
 * @param array<string, string> $namespaces
 *
 * @return Closure(Element): Element
 */
function xmlns_inheriting_element(string $name, array $children, ?array $namespaces = []): Closure
{
    return static function (XMLDocument|Element $parent) use ($namespaces, $name, $children): Element {

        $defaultNamespace = $namespaces[''] ?? null;

        // These rules apply for non prefixed elements only:
        // If no local namespace has been defined: lookup the default local namespace of the closest parent element.
        // Use that specific local namespace to create the element if one could be found.
        // Otherwise, just create a non-namespaced element.
        if (!is_prefixed_node_name($name)) {
            // Try to find the inherited default XMLNS for non prefixed elements without a desired local namespace.
            if ($defaultNamespace === null && is_element($parent)) {
                $defaultNamespace = $parent->lookupNamespaceURI('');
            }

            return $defaultNamespace !== null
                ? namespacedElementBuilder($defaultNamespace, $name, ...$children)($parent)
                : elementBuilder($name, ...$children)($parent);
        }

        // Prefixed elements can be created as regular elements:
        // The configured xmlns attributes will be added by the $children.
        // If a local namespace is configured, make sure to register it on the node manually.
        [$prefix] = explode(':', $name);
        $prefixedNamespace = $namespaces[$prefix] ?? (is_element($parent) ? $parent->lookupNamespaceURI($prefix) : null);

        Assert::notNull($prefixedNamespace, 'No namespace URI could be found for prefix: '.$prefix);

        $defaultXmlns = $defaultNamespace !== null ? [default_xmlns_attribute($defaultNamespace)] : [];
        return namespacedElementBuilder(
            $prefixedNamespace,
            $name,
            ...$defaultXmlns,
            ...$children,
        )($parent);
    };
}
