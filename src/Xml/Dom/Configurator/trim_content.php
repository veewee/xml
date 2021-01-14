<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function trim_content(): callable
{
    return static function (DOMDocument $document): DOMDocument {
        $document->preserveWhiteSpace = false;
        $document->formatOutput = false;

        return $document;
    };
}
