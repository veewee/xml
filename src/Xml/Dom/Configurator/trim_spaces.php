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
function trim_spaces(): Closure
{
    return static function (XMLDocument $document): XMLDocument {
        $trimmed = Document::fromLoader(
            xml_document_loader($document, LIBXML_NOBLANKS)
        )->toUnsafeDocument();

        $trimmed->formatOutput = false;

        return $trimmed;
    };
}
