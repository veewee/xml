<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;
use DOMNameSpaceNode;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xmlns\Xmlns;
use function Psl\Dict\merge;
use function Psl\Str\format;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Locator\Namespaces\recursive_linked_namespaces;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function optimize_namespaces(string $prefix = 'test'): callable
{
    return static function (DOMDocument $document) use ($prefix) : DOMDocument {
        /** @var array<string, true> $namespaces */
        $namespaces = recursive_linked_namespaces($document)->reduce(
            static function (array $namespaces, DOMNameSpaceNode $node): array {
                if (Xmlns::xml()->matches(Xmlns::load($node->namespaceURI))) {
                    return $namespaces;
                }

                return merge($namespaces, [$node->namespaceURI => true]);
            },
            []
        );


        $doc = Document::fromUnsafeDocument($document);
        $root = $doc->map(document_element());

        $i = 0;
        foreach ($namespaces as $namespace => $_) {
            attribute(format('xmlns:%s%s', $prefix, (++$i)), $namespace)($root);
        }

        return canonicalize()($document);
    };
}
