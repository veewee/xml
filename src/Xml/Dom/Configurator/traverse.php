<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor;

/**
 * @no-named-arguments
 * @param list<Visitor> $visitors
 *
 * @return Closure(DOMDocument): DOMDocument
 */
function traverse(Visitor ... $visitors): Closure
{
    return static function (DOMDocument $document) use ($visitors): DOMDocument {
        Document::fromUnsafeDocument($document)->traverse(...$visitors);

        return $document;
    };
}
