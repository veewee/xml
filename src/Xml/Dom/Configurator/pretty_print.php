<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Loader\xml_document_loader;

/**
 * @return Closure(XMLDocument): XMLDocument
 */
function pretty_print(): Closure
{
    return static function (XMLDocument $document): XMLDocument {
        $prettyPrinted = Document::fromLoader(
            xml_document_loader($document, LIBXML_NOBLANKS)
        )->toUnsafeDocument();

        $prettyPrinted->formatOutput = true;

        return $prettyPrinted;
    };
}
