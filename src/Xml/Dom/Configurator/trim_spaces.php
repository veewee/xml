<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function trim_spaces(): Closure
{
    return static function (DOMDocument $document): DOMDocument {
        $document->preserveWhiteSpace = false;
        $document->formatOutput = false;

        return $document;
    };
}
