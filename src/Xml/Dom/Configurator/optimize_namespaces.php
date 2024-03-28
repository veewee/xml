<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;
use function VeeWee\Xml\Dom\Manipulator\Document\optimize_namespaces as optimize_namespaces_manipulator;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function optimize_namespaces(string $prefix = 'ns'): Closure
{
    return static function (\DOM\XMLDocument $document) use ($prefix) : \DOM\XMLDocument {
        optimize_namespaces_manipulator($document, $prefix);

        return $document;
    };
}
