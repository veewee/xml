<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;

/**
 * @param callable(\DOM\XMLDocument): void $loader
 *
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function loader(callable $loader): Closure
{
    return static function (\DOM\XMLDocument $document) use ($loader): \DOM\XMLDocument {
        $loader($document);
        return $document;
    };
}
