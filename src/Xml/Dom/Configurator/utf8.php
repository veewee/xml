<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function utf8(): Closure
{
    return static function (\DOM\XMLDocument $document): \DOM\XMLDocument {
        $document->charset = 'UTF-8';

        return $document;
    };
}
