<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function pretty_print(): Closure
{
    return static function (DOMDocument $document): DOMDocument {
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        return $document;
    };
}
