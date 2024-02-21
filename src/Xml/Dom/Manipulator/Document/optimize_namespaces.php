<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Document;

use \DOM\XMLDocument;
use \DOM\NameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use function Psl\Dict\unique;
use function Psl\Vec\sort;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Locator\Xmlns\recursive_linked_namespaces;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename;

/**
 * @throws RuntimeException
 */
function optimize_namespaces(\DOM\XMLDocument $document, string $prefix = 'ns'): void
{
    $namespaceURIs = recursive_linked_namespaces($document)
        ->filter(static fn (\DOM\NameSpaceNode $node): bool => $node->namespaceURI !== Xmlns::xml()->value())
        ->reduce(
            /**
             * @param list<string> $grouped
             * @return list<string>
             */
            static fn (array $grouped, \DOM\NameSpaceNode $node): array
                => values(unique([...$grouped, $node->namespaceURI])),
            []
        );

    foreach (sort($namespaceURIs) as $index => $namespaceURI) {
        rename($document, $namespaceURI, $prefix . ((string) ($index+1)));
    }
}
