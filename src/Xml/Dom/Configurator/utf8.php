<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function utf8(): Closure
{
    return static function (DOMDocument $document): DOMDocument {
        $document->encoding = 'UTF-8';

        return $document;
    };
}
