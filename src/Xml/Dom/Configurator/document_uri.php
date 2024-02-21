<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;

/**
 * @param non-empty-string $documentUri
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function document_uri(string $documentUri): Closure
{
    return static function (\DOM\XMLDocument $document) use ($documentUri) : \DOM\XMLDocument {
        $document->documentURI = $documentUri;

        return $document;
    };
}
