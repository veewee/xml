<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;

/**
 * @param non-empty-string $documentUri
 * @return Closure(XMLDocument): XMLDocument
 */
function document_uri(string $documentUri): Closure
{
    return static function (XMLDocument $document) use ($documentUri) : XMLDocument {
        $document->documentURI = $documentUri;

        return $document;
    };
}
