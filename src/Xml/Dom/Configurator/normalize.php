<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function normalize(): Closure
{
    return static function (DOMDocument $document): DOMDocument {
        $document->normalizeDocument();

        return $document;
    };
}
