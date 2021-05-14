<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Namespaces;

use DOMDocument;
use DOMElement;
use DOMNameSpaceNode;
use DOMNode;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Dom\Xpath;
use VeeWee\Xml\Exception\RuntimeException;
use function sprintf;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Attributes\attributes_list;
use function VeeWee\Xml\Dom\Locator\Attributes\xmlns_attributes_list;
use function VeeWee\Xml\Dom\Manipulator\Node\remove_namespace;
use function VeeWee\Xml\Dom\Manipulator\Node\rename as rename_node;
use function VeeWee\Xml\Dom\Predicate\is_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @throws RuntimeException
 */
function rename(DOMDocument $document, string $namespaceURI, string $newPrefix): void
{
    // Check for prefix collisions
    if (($existingUri = $document->lookupNamespaceURI($newPrefix)) && $existingUri !== $namespaceURI) {
        throw RuntimeException::withMessage(
            sprintf(
                'Cannot rename the namespace uri %s because the prefix %s is already linked to uri %s',
                $namespaceURI,
                $newPrefix,
                $existingUri
            )
        );
    }

    $xpath = Xpath::fromUnsafeNode($document);
    $predicate = static fn (DOMNode $node): bool
        => $node->namespaceURI === $namespaceURI && $node->prefix !== $newPrefix;

    // Fetch all nodes (attributes and elements) linked to the given namespace and the nodes that declare namespaces.
    // The sort order is important:
    // Make sure to set the deepest DOM element first in the list
    // The attributes need to be dealt with as last,
    // otherwise XMLNS namespace will be removed again after dealing with the elements that declare the xmlns.
    $linkedNodes = $xpath->query(
        sprintf('//*[namespace-uri()=\'%1$s\' or @*[namespace-uri()=\'%1$s\'] or namespace::*]', $namespaceURI)
    )->reduce(
        static fn (NodeList $list, DOMElement $element): NodeList
            => new NodeList(
                ...[$element],
                ...$list,
                ...attributes_list($element)->filter($predicate),
            ),
        new NodeList()
    );

    // Add new xmlns to root node
    $root = $document->documentElement;
    xmlns_attribute($newPrefix, $namespaceURI)($root);

    // Go through the linked nodes and remove all matching xmlns attributes
    // Finally rename the node in order to use the new prefix.
    $linkedNodes->forEach(
        static function (DOMNode $node) use ($namespaceURI, $newPrefix, $predicate): void {
            // Wrapped in a closure so that psalm knows it all...
            $newQname = static fn (DOMNode $node): string => $newPrefix.':'.$node->localName;

            if (is_attribute($node)) {
                rename_node($node, $newQname($node), $namespaceURI);
                return;
            }

            // @codeCoverageIgnoreStart
            if (!is_element($node)) {
                return;
            }
            // @codeCoverageIgnoreEnd

            // Remove old xmlns declarations:
            xmlns_attributes_list($node)
                ->filter(
                    static fn (DOMNameSpaceNode $xmlns): bool
                        => $xmlns->namespaceURI === $namespaceURI && $xmlns->prefix !== $newPrefix
                )
                ->forEach(
                    static function (DOMNameSpaceNode $xmlns) use ($node) {
                        remove_namespace($xmlns, $node);
                    }
                );

            // If the DOM element is part of the namespace URI, rename it!
            // (Remember: this function also accepts regular DOM elements with xmlns declarations that are not linked to the namespace)
            if ($predicate($node)) {
                rename_node($node, $newQname($node), $namespaceURI);
            }
        }
    );
}
