<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function normalize(): callable
{
    return static function (DOMDocument $document): DOMDocument {
        $document->normalizeDocument();

        return $document;
    };
}
