<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use function VeeWee\Xml\Dom\Manipulator\Document\optimize_namespaces as optimize_namespaces_manipulator;

/**
 * @return Closure(XMLDocument): XMLDocument
 */
function optimize_namespaces(string $prefix = 'ns'): Closure
{
    return static function (XMLDocument $document) use ($prefix) : XMLDocument {
        optimize_namespaces_manipulator($document, $prefix);

        return $document;
    };
}
