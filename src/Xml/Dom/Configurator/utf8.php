<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \Dom\XMLDocument;

/**
 * @return Closure(\Dom\XMLDocument): \Dom\XMLDocument
 */
function utf8(): Closure
{
    return static function (\Dom\XMLDocument $document): \Dom\XMLDocument {
        $document->charset = 'UTF-8';

        return $document;
    };
}
