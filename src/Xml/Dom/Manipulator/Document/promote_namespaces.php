<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Document;

use Dom\Attr;
use Dom\XMLDocument;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\pull;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Manipulator\Node\remove_namespace;

/**
 * @throws RuntimeException
 */
function promote_namespaces(XMLDocument $document): void
{
    $documentElement = document_element()($document);

    /** @var array<string, string> $promoted prefix => URI */
    $promoted = pull(
        xmlns_attributes_list($documentElement)->filter(static fn (Attr $attr): bool => $attr->prefix !== null),
        static fn (Attr $attr): string => $attr->value,
        static fn (Attr $attr): string => $attr->localName,
    );

    foreach ($documentElement->getElementsByTagName('*') as $element) {
        $prefixedXmlns = xmlns_attributes_list($element)
            ->filter(static fn (Attr $attr): bool => $attr->prefix !== null);

        foreach ($prefixedXmlns as $attr) {
            $prefix = $attr->localName;
            $uri = $attr->value;

            if (!array_key_exists($prefix, $promoted)) {
                xmlns_attribute($prefix, $uri)($documentElement);
                $promoted[$prefix] = $uri;
            }

            if ($promoted[$prefix] === $uri) {
                remove_namespace($attr, $element);
            }
        }
    }
}
