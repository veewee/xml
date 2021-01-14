<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function utf8(): callable
{
    return static function (DOMDocument $document): DOMDocument {
        $document->encoding = 'UTF-8';

        return $document;
    };
}
