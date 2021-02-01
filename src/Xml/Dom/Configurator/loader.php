<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;

/**
 * @param callable(DOMDocument): void $loader
 *
 * @return callable(DOMDocument): DOMDocument
 */
function loader(callable $loader): callable
{
    return static function (DOMDocument $document) use ($loader): DOMDocument {
        $loader($document);
        return $document;
    };
}
