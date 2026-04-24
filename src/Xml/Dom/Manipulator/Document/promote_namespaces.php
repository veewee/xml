<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Document;

use DOMDocument;
use DOMNameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\pull;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Manipulator\Node\remove_namespace;

/**
 * Moves prefixed xmlns declarations from descendant nodes up to the document
 * element while keeping the original prefix names. Default namespaces and
 * prefixes that conflict with a declaration already on the document element
 * are left untouched.
 *
 * Note — legacy DOM quirk: calling setAttributeNS with the xmlns URI triggers
 * libxml's namespace reconciliation. In documents where two descendants
 * declare the same prefix for different URIs, libxml rewrites the second
 * subtree's element prefixes (e.g. `a` -> `a1`) and pulls the extra xmlns up
 * to the root. The result is still semantically equivalent XML, but opaque
 * prefix references inside attribute values (e.g. xsi:type="a:Thing") are
 * not rewritten and may resolve against the shadowing declaration on the
 * original subtree. Upgrade to veewee/xml 4.x if exact prefix preservation
 * matters for your consumers.
 *
 * @throws RuntimeException
 */
function promote_namespaces(DOMDocument $document): void
{
    $documentElement = document_element()($document);

    /** @var array<string, string> $promoted prefix => URI */
    $promoted = pull(
        xmlns_attributes_list($documentElement)
            ->filter(static fn (DOMNameSpaceNode $attr): bool => $attr->prefix !== ''),
        static fn (DOMNameSpaceNode $attr): string => $attr->namespaceURI,
        static fn (DOMNameSpaceNode $attr): string => $attr->prefix,
    );

    foreach ($documentElement->getElementsByTagName('*') as $element) {
        $prefixedXmlns = xmlns_attributes_list($element)
            ->filter(static fn (DOMNameSpaceNode $attr): bool => $attr->prefix !== '');

        foreach ($prefixedXmlns as $attr) {
            $prefix = $attr->prefix;
            $uri = $attr->namespaceURI;

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
