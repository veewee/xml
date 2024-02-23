<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function normalize(): Closure
{
    return static function (\DOM\XMLDocument $document): \DOM\XMLDocument {
        $document->normalize();

        return $document;
    };
}
