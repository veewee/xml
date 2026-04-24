<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;
use function VeeWee\Xml\Dom\Manipulator\Document\promote_namespaces as promote_namespaces_manipulator;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function promote_namespaces(): Closure
{
    return static function (DOMDocument $document): DOMDocument {
        promote_namespaces_manipulator($document);

        return $document;
    };
}
