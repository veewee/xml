<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor\OptimizeNamespaces;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function optimize_namespaces(string $prefix = 'test'): callable
{
    return static function (DOMDocument $document) use ($prefix) : DOMDocument {
        Document::fromUnsafeDocument($document)->traverse(new OptimizeNamespaces());

        return canonicalize()($document);
    };
}
