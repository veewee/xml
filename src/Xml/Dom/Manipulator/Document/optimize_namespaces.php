<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Document;

use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Dict\unique;
use function Psl\Vec\map;
use function Psl\Vec\sort;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Locator\Xmlns\recursive_linked_namespaces;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename_element_namespace;

/**
 * @throws RuntimeException
 */
function optimize_namespaces(\DOM\XMLDocument $document, string $prefix = 'ns'): void
{
    $documentElement = $document->documentElement;
    $namespaceURIs = values(unique(map(
        recursive_linked_namespaces($documentElement),
        static fn (\DOM\NamespaceInfo $info): string => $info->namespaceURI
    )));

    foreach (sort($namespaceURIs) as $index => $namespaceURI) {
        rename_element_namespace($documentElement, $namespaceURI, $prefix . ((string) ($index+1)));
    }
}
