<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;

/**
 * @return Closure(XMLDocument): XMLDocument
 */
function utf8(): Closure
{
    return static function (XMLDocument $document): XMLDocument {
        $document->charset = 'UTF-8';

        return $document;
    };
}
