<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \Dom\XMLDocument;

/**
 * @param non-empty-string $documentUri
 * @return Closure(\Dom\XMLDocument): \Dom\XMLDocument
 */
function document_uri(string $documentUri): Closure
{
    return static function (\Dom\XMLDocument $document) use ($documentUri) : \Dom\XMLDocument {
        $document->documentURI = $documentUri;

        return $document;
    };
}
