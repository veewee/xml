<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor;

/**
 * @no-named-arguments
 * @param list<Visitor> $visitors
 *
 * @return Closure(XMLDocument): XMLDocument
 */
function traverse(Visitor ... $visitors): Closure
{
    return static function (XMLDocument $document) use ($visitors): XMLDocument {
        Document::fromUnsafeDocument($document)->traverse(...$visitors);

        return $document;
    };
}
