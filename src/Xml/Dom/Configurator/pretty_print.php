<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function pretty_print(): callable
{
    return static function (DOMDocument $document): DOMDocument {
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        return $document;
    };
}
