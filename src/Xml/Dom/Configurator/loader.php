<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;

/**
 * @param \Closure(DOMDocument): void $loader
 *
 * @return \Closure(DOMDocument): DOMDocument
 */
function loader(Closure $loader): Closure
{
    return static function (DOMDocument $document) use ($loader): DOMDocument {
        $loader($document);
        return $document;
    };
}
