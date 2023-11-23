<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;

/**
 * @param non-empty-string $documentUri
 * @return Closure(DOMDocument): DOMDocument
 */
function document_uri(string $documentUri): Closure
{
    return static function (DOMDocument $document) use ($documentUri) : DOMDocument {
        $document->documentURI = $documentUri;

        return $document;
    };
}
