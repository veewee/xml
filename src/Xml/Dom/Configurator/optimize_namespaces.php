<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;
use function VeeWee\Xml\Dom\Manipulator\Document\optimize_namespaces as optimize_namespaces_manipulator;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function optimize_namespaces(string $prefix = 'ns'): Closure
{
    return static function (DOMDocument $document) use ($prefix) : DOMDocument {
        optimize_namespaces_manipulator($document, $prefix);

        return $document;
    };
}
